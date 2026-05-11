<?php

namespace App\Livewire\Departments;

use App\Models\Committee;
use App\Models\Department;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Departments extends Component
{
    use WithPagination;

    #[Url]
    public string $committee = "";

    public function mount(Request $request)
    {
        if ($request->committee) {
            $this->committee = $request->committee;
        } else {
            $committee = Committee::orderBy('name')->first();
            $this->committee = $committee->id;
        }
    }

    public function render()
    {
        $committees = Committee::orderBy('name')->get();

        $departments = Department::orderBy('name')
            ->where('committee', $this->committee)
            ->paginate(10);

        return view('livewire.departments.departments', [
            'committees' => $committees,
            'departments' => $departments,
        ]);
    }
}
