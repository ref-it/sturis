<?php

namespace App\Livewire\Agenda;

use App\Models\AgendaItem;
use App\Models\Committee;
use App\Models\Goal;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddItem extends Component
{
    public $committee;
    public $committeeID;

    public $id;
    public $parentItem;

    public int $meeting;

    #[Validate('required')]
    public string $title = "";

    #[Validate('required')]
    public array $people = [];

    #[Validate('required|numeric|gt:0')]
    public int $expectedDuration = 0;

    #[Validate('required')]
    public array $goal = [];

    public bool $externalGuests = false;

    public bool $internal = false;

    #[Validate('required')]
    public string $text = "";

    private function determineItemTitle($minutesStructure)
    {
        foreach ($minutesStructure as $item) {
            if ($item->id === (int) $this->id) {
                return $item->title;
            } elseif (count($item->children) > 0) {
                $result = $this->determineItemTitle($item->children);
                if ($result !== "") {
                    return $result;
                }
            }
        }
        return "";
    }

    public function mount($committee, $meeting, Request $request)
    {
        $this->committee = $committee;
        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;

        $this->id = $request->id;
        $this->parentItem = $request->parentID;

        if (!$request->parentItem) {
            $meeting = Meeting::where('id', $meeting)->first();
            $minutesStructure = json_decode($meeting->minutes_structure);
            $this->title = $this->determineItemTitle($minutesStructure);
        }
    }

    public function render()
    {
        $goals = Goal::where('committee', $this->committeeID)->get();
        $users = User::get();

        return view('livewire.agenda.add-item', [
            'goals' => $goals,
            'users' => $users,
        ]);
    }

    public function save()
    {
        $this->validate();

        $order = 0;
        if ($this->parentItem) {
            $maxOrder = AgendaItem::select('order')
                ->where('committee', $this->committeeID)
                ->where('meeting', $this->meeting)
                ->where('parent', $this->parentItem)
                ->max('order');
            
            if ($maxOrder !== null) {
                $order = $maxOrder + 1;
            }
        }

        AgendaItem::create([
            'committee' => $this->committeeID,
            'meeting' => $this->meeting,
            'structure_id' => $this->id,
            'parent' => $this->parentItem,
            'order' => $order,
            'title' => $this->title,
            'text' => $this->text,
            'people' => !empty($this->people) ? json_encode($this->people) : '[]',
            'expected_duration' => $this->expectedDuration ?? 0,
            'goals' => !empty($this->goal) ? json_encode($this->goal) : '[]',
            'guest' => $this->externalGuests,
            'internal' => $this->internal,
        ]);

        $this->redirect('/' . $this->committee . '/meetings/' . $this->meeting . '/agenda', navigate: true);
    }
}
