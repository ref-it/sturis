<?php

namespace App\Livewire\Agenda;

use App\Facades\DokuWiki;
use App\Facades\Minutes;
use App\Models\AgendaItem;
use App\Models\Attachment;
use App\Models\Committee;
use App\Models\Goal;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class Agenda extends Component
{
    #[Locked]
    public string $committee = "";

    #[Locked]
    public ?int $committeeID;

    #[Locked]
    public ?int $meetingID;

    public $items;

    #[Locked]
    public $calendar;

    public function mount($committee, $meeting)
    {
        $this->committee = $committee;
        $this->meetingID = $meeting;

        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;
    }

    private function getNamesById($people)
    {
        $peopleIDs = json_decode($people);
        $peopleNames = [];
        foreach ($peopleIDs as $p) {
            $user = User::where('id', $p)->first();
            $peopleNames[] = $user->name;
        }
        return $peopleNames;
    }

    private function getGoalsById($goals)
    {
        $goalIDs = json_decode($goals);
        $goalNames = [];
        foreach ($goalIDs as $g) {
            $goal = Goal::where('id', $g)->first();
            $goalNames[] = $goal->name;
        }
        return $goalNames;
    }

    private function checkIfContentExists($minutesStructure)
    {
        foreach ($minutesStructure as $msItem) {
            $agendaItem = AgendaItem::where('committee', $this->committeeID)
                ->where('meeting', $this->meetingID)
                ->where('structure_id', $msItem->id)
                ->first();

            if ($agendaItem) {
                $msItem->itemID = $agendaItem->id;
                $msItem->contentExists = true;
                $msItem->expected_duration = $agendaItem->expected_duration;
                $msItem->goals = $agendaItem->goals;
                $msItem->guest = $agendaItem->guest;
                $msItem->internal = $agendaItem->internal;
                $msItem->people = $this->getNamesById($agendaItem->people);
                $msItem->goals = $this->getGoalsById($agendaItem->goals);
                $msItem->hasParent = false;
            } else {
                $msItem->contentExists = false;
                $msItem->hasParent = false;
            }

            if (count($msItem->children) > 0) {
                $this->checkIfContentExists($msItem->children);
            }
        }

        return $minutesStructure;
    }

    private function addFreeChildren($minutesStructure)
    {
        foreach ($minutesStructure as $msItem) {
            $items = AgendaItem::where('committee', $this->committeeID)
                ->where('meeting', $this->meetingID)
                ->where('parent', $msItem->id)
                ->orderBy('order')
                ->get();

            foreach($items as $item) {
                $msItem->children[] = (object) [
                    'itemID' => $item->id,
                    'title' => $item->title,
                    'editable' => true,
                    'agendaItemsAsChildren' => false,
                    'contentExists' => true,
                    'children' => [],
                    'guest' => $item->guest,
                    'internal' => $item->internal,
                    'guest' => $item->guest,
                    'internal' => $item->internal,
                    'expected_duration' => $item->expected_duration,
                    'people' => $this->getNamesById($item->people),
                    'goals' => $this->getGoalsById($item->goals),
                    'hasParent' => true,
                ];

                if ($item->agendaItemsAsChildren) {
                    $this->addFreeChildren($msItem->children);
                }
            }
        }

        return $minutesStructure;
    }

    public function render()
    {
        $committee = Committee::where('token', $this->committee)->first();

        $meeting = Meeting::where('committee', $this->committeeID)
            ->where('id', $this->meetingID)
            ->first();

        $minutesStructure = [];
        if (json_validate($meeting->minutes_structure)) {
            $minutesStructure = json_decode($meeting->minutes_structure);
        } else {
            // Error decoding JSON
        }

        if (count($minutesStructure) > 0) {
            $minutesStructure = $this->checkIfContentExists($minutesStructure);
            $minutesStructure = $this->addFreeChildren($minutesStructure);
        }

        $nextMeeting = Meeting::select('id', 'date')
            ->where('committee', $this->committeeID)
            ->where('date', '>', $meeting->date)
            ->orderBy('date')
            ->first();

        return view('livewire.agenda.agenda', [
            'committeeName' => $committee->name,
            'minutesStructure' => $minutesStructure,
            'meeting' => $meeting,
            'nextMeeting' => $nextMeeting,
        ]);
    }

    public function saveEvent()
    {
        $committee = Committee::where('token', $this->committee)->first();
        $meeting = Meeting::where('id', $this->meetingID)->first();

        $expectedDuration = AgendaItem::where('committee', $this->committeeID)
            ->where('meeting', $this->meetingID)
            ->sum('expected_duration');

        $timezone = "Europe/Berlin";
        $meetingStart = new DateTime($meeting->date . ' ' . $meeting->time, new DateTimeZone($timezone));
        $meetingEnd = clone $meetingStart;
        $meetingEnd->add(DateInterval::createFromDateString($expectedDuration . ' minutes'));
        $calendar = Calendar::create()->name($committee->name);
        $event = Event::create()
            ->startsAt($meetingStart)
            ->endsAt($meetingEnd)
            ->uniqueIdentifier($meeting->uid);
        if ($meeting->address) {
            $event->address($meeting->address);
        }
        if ($meeting->room) {
            $event->addressName($meeting->room);
        }
        if ($meeting->latitude && $meeting->longitude) {
            $event->coordinates($meeting->latitude, $meeting->longitude);
        }
        $calendar->event($event);
        $this->calendar = $calendar->get();

        return response()->streamDownload(function () {
                echo $this->calendar;
            },  $this->committee . '_' . $meeting->date . '.ics');

    }

    public function sortFreeItems($id, $position)
    {
        $movedItem = AgendaItem::where('id', $id)->first();
        if (!$movedItem) return;

        $oldPosition = $movedItem->order;
        $newPosition = $position;

        // Get all sibling items
        $allItems = AgendaItem::where('parent', $movedItem->parent)
            ->where('meeting', $this->meetingID)
            ->orderBy('order')
            ->get();

        if ($oldPosition < $newPosition) {
            // Moving down - shift items between oldPosition+1 and newPosition down by 1
            foreach ($allItems as $item) {
                if ($item->id !== $id && $item->order > $oldPosition && $item->order <= $newPosition) {
                    $item->update(['order' => $item->order - 1]);
                }
            }
        } elseif ($oldPosition > $newPosition) {
            // Moving up - shift items between newPosition and oldPosition-1 up by 1
            foreach ($allItems as $item) {
                if ($item->id !== $id && $item->order >= $newPosition && $item->order < $oldPosition) {
                    $item->update(['order' => $item->order + 1]);
                }
            }
        }

        // Set moved item to new position
        $movedItem->update(['order' => $newPosition]);
    }

    public function postponeItemUntilNextMeeting($id)
    {
        $meeting = Meeting::select('date')
            ->where('committee', $this->committeeID)
            ->where('id', $this->meetingID)
            ->first();

        $nextMeeting = Meeting::select('id')
            ->where('committee', $this->committeeID)
            ->where('date', '>', $meeting->date)
            ->orderBy('date')
            ->first();

        AgendaItem::where('id', $id)->update([
            'meeting' => $nextMeeting->id,
        ]);

        return redirect()->back();
    }

    public function deleteItem($id)
    {
        // Delete linked motions
        Motion::where('agenda_item', $id)->delete();

        // Delete linked attachments
        $attachments = Attachment::where('agenda_item', $id)->get();
        foreach ($attachments as $a) {
            // TODO Delete file from storage
            $a->delete();
        }

        // Get the item
        $item = AgendaItem::where('id', $id)->first();

        // Get the items parent if one exists
        if ($item->parent) {
            $itemParent = $item->parent;
        }

        // Delete the item
        $item->delete();

        if ($itemParent) {
            // Get all siblings of this item
            $siblings = AgendaItem::where('committee', $this->committeeID)
                ->where('meeting', $this->meetingID)
                ->where('parent', $itemParent)
                ->orderBy('order')
                ->get();

            // Update the order
            foreach ($siblings as $index => $s) {
                $s->update([
                    'order' => $index,
                ]);
            }
        }

        return redirect()->back();
    }

    public function saveAsPdf()
    {
        $committee = Committee::where('token', $this->committee)->first();

        $meeting = Meeting::where('committee', $this->committeeID)
            ->where('id', $this->meetingID)
            ->first();

        $minutesStructure = [];
        if (json_validate($meeting->minutes_structure)) {
            $minutesStructure = json_decode($meeting->minutes_structure);
        } else {
            // Error decoding JSON
        }

        if (count($minutesStructure) > 0) {
            $minutesStructure = $this->checkIfContentExists($minutesStructure);
            $minutesStructure = $this->addFreeChildren($minutesStructure);
        }

        $pdf = Pdf::loadView('pdfs.agenda', [
            'agenda' => $minutesStructure,
            'committee' => $committee->name,
            'meeting' => $meeting,
        ]);

        return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, strtolower(__('messages.agenda')) . '_' . $this->committee . '_' .$meeting->date . '.pdf');
    }

    public function createMinutes()
    {
        Minutes::generate($this->committeeID, $this->meetingID);
    }
}
