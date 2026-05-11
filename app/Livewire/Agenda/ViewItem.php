<?php

namespace App\Livewire\Agenda;

use App\Models\AgendaItem;
use App\Models\Attachment;
use App\Models\Goal;
use App\Models\Motion;
use App\Models\User;
use Livewire\Component;

class ViewItem extends Component
{
    public $committee;
    public $meeting;
    public $id;

    private function getPeopleAndGoals($item)
    {
        $peopleIDs = json_decode($item->people);
        $people = [];
        foreach ($peopleIDs as $p) {
            $user = User::where('id', $p)->first();
            $people[] = $user->name;
        }
        $item->people = $people;

        $goalIDs = json_decode($item->goals);
        $goals = [];
        foreach ($goalIDs as $g) {
            $goal = Goal::where('id', $g)->first();
            $goals[] = $goal->name;
        }
        $item->goals = $goals;

        return $item;
    }

    public function mount($committee, $meeting, $id)
    {
        $this->committee = $committee;
        $this->meeting = $meeting;
        $this->id = $id;
    }

    public function render()
    {
        $item = AgendaItem::where('id', $this->id)->first();
        $item = $this->getPeopleAndGoals($item);

        if (!$item) {
            abort(404);
        }

        $motions = Motion::where('agenda_item', $this->id)->get();
        $attachments = Attachment::where('agenda_item', $this->id)->get();

        return view('livewire.agenda.view-item', [
            'item' => $item,
            'motions' => $motions,
            'attachments' => $attachments,
        ]);
    }
}
