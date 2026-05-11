<?php

namespace App\Livewire\Groups;

use App\Models\Committee;
use App\Models\Group;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.start')]
class AddGroup extends Component
{
    public ?int $committee;

    public string $name = "";

    public function render()
    {
        $committees = Committee::orderBy('name')->get();
        return view('livewire.groups.add-group', [
            'committees' => $committees,
        ]);
    }

    public function save()
    {
        Group::create([
            'committee' => $this->committee,
            'name' => $this->name,
        ]);

        $this->redirect('/groups', navigate: true);
    }
}
