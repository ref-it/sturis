<?php

namespace App\Livewire\Agenda;

use App\Models\AgendaItem;
use App\Models\Committee;
use App\Models\Goal;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditItem extends Component
{
    public $committee;
    public ?int $committeeID;
    public ?int $itemID;
    public ?int $meeting;

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

    public ?int $parent = null;

    public function mount($committee, $meeting, $id)
    {
        $this->committee = $committee;
        $this->meeting = $meeting;
        $this->itemID = $id;

        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;

        $item = AgendaItem::where('id', $id)->first();
        if ($item) {
            $this->parent = $item->parent;
            $this->title = $item->title;
            $this->text = $item->text;
            $this->expectedDuration = $item->expected_duration ?? 0;
            $this->externalGuests = $item->guest ?? false;
            $this->internal = $item->internal ?? false;
            $this->people = json_decode($item->people, true) ?? [];
            $this->goal = json_decode($item->goals, true) ?? [];
        }
    }

    public function render()
    {
        $goals = Goal::where('committee', $this->committeeID)->get();
        $users = User::get();

        return view('livewire.agenda.edit-item', [
            'goals' => $goals,
            'users' => $users,
        ]);
    }

    public function save()
    {
        $this->validate();

        AgendaItem::where('id', $this->itemID)->update([
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
