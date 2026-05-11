<?php

namespace App\Livewire\Goals;

use App\Models\Committee;
use App\Models\Goal;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.start')]
class EditGoal extends Component
{
    public ?int $goalID = null;

    #[Validate('required|integer|exists:committees,id')]
    public ?int $committee = null;

    #[Validate('required|string|min:3|max:255')]
    public string $name = "";

    public function mount($id)
    {
        $this->goalID = $id;

        $goal = Goal::where('id', $id)->first();
        if ($goal) {
            $this->committee = $goal->committee;
            $this->name = $goal->name;
        }
    }

    public function render()
    {
        $committees = Committee::orderBy('name')->get();

        return view('livewire.goals.edit-goal', [
            'committees' => $committees,
        ]);
    }

    public function save()
    {
        $this->validate();

        Goal::where('id', $this->goalID)->update([
            'committee' => $this->committee,
            'name' => $this->name,
        ]);

        $this->redirect(route('goals', ['committee' => $this->committee]), navigate: true);
    }
}
