<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use App\Models\Department;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Departments extends Component
{
    use WithPagination;

    #[Locked]
    public string $token = "";

    public string $departmentName = "";
    public ?int $departmentID;

    public function mount($committee)
    {
        $this->token = $committee;
    }

    public function render()
    {
        $committee = Committee::where('token', $this->token)->first();

        $departments = Department::orderBy('name')
            ->where('committee', $committee->id)
            ->paginate(10);

        return view('livewire.committees.departments', [
            'committee' => $committee,
            'departments' => $departments,
        ]);
    }

    public function addDepartment()
    {
        $committee = Committee::select('id')->where('token', $this->token)->first();

        $this->validate([
            'departmentName' => 'required',
        ]);

        Department::create([
            'committee' => $committee->id,
            'name' => $this->departmentName,
        ]);

        Flux::modal('new')->close();
        return redirect()->back();
    }

    public function openEditModal($departmentID)
    {
        $department = Department::select('id', 'name')->where('id', $departmentID)->first();
        if ($department) {
            $this->departmentID = $department->id;
            $this->departmentName = $department->name;
        }

        Flux::modal('edit')->show();
    }

    public function updateDepartment()
    {
        $committee = Committee::select('id')->where('token', $this->token)->first();

        $this->validate([
            'departmentName' => 'required',
        ]);

        Department::where('id', $this->departmentID)->update([
            'name' => $this->departmentName,
        ]);

        Flux::modal('edit')->close();
        return redirect()->back();
    }
}
