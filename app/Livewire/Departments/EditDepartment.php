<?php

namespace App\Livewire\Departments;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.start')]
class EditDepartment extends Component
{
    public function render()
    {
        return view('livewire.departments.edit-department');
    }
}
