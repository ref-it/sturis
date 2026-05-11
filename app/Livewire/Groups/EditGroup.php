<?php

namespace App\Livewire\Groups;

use App\Models\Committee;
use App\Models\Group;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.start')]
class EditGroup extends Component
{
    public $id;

    public ?int $committee;

    public string $name = "";

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $committees = Committee::orderBy('name')->get();

        $group = Group::where('id', $this->id)->first();
        $this->committee = $group->committee;
        $this->name = $group->name;

        return view('livewire.groups.edit-group', [
            'committees' => $committees,
        ]);
    }
}
