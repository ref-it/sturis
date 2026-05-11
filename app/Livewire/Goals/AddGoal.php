<?php

namespace App\Livewire\Goals;

use App\Models\Committee;
use App\Models\Goal;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.start')]
class AddGoal extends Component
{
    #[Validate('required|integer|exists:committees,id')]
    public ?int $committee = null;

    #[Validate('required|string|min:3|max:255')]
    public string $name = "";

    public function render()
    {
        $committees = Committee::orderBy('name')->get();

        return view('livewire.goals.add-goal', [
            'committees' => $committees,
        ]);
    }

    public function save()
    {
        $this->validate();

        Goal::create([
            'committee' => $this->committee,
            'name' => $this->name,
        ]);

        $this->redirect(route('goals', ['committee' => $this->committee]), navigate: true);
    }
}
