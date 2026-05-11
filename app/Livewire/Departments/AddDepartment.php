<?php

namespace App\Livewire\Departments;

use App\Models\Committee;
use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.start')]
class AddDepartment extends Component
{
    public ?int $committee;
    public string $name = "";

    public function render()
    {
        $committees = Committee::orderBy('name')->get();
        return view('livewire.departments.add-department', [
            'committees' => $committees,
        ]);
    }

    public function save()
    {
        Department::create([
            'committee' => $this->committee,
            'name' => $this->name,
        ]);

        $this->redirect('/departments', navigate: true);
    }
}
