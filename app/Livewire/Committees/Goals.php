<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use App\Models\Goal;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Goals extends Component
{
    use WithPagination;

    #[Locked]
    public string $token = "";

    public string $goalName = "";
    public ?int $goalID;

    public function mount($committee)
    {
        $this->token = $committee;
    }

    public function render()
    {
        $committee = Committee::where('token', $this->token)->first();
        $this->isActive = $committee->active;

        $goals = Goal::orderBy('name')
            ->where('committee', $committee->id)
            ->paginate(10);

        return view('livewire.committees.goals', [
            'committee' => $committee,
            'goals' => $goals,
        ]);
    }

    public function addGoal()
    {
        $committee = Committee::select('id')->where('token', $this->token)->first();

        $this->validate([
            'goalName' => 'required',
        ]);

        Goal::create([
            'committee' => $committee->id,
            'name' => $this->goalName,
        ]);

        Flux::modal('new')->close();
        return redirect()->back();
    }

    public function openEditModal($goalID)
    {
        $goal = Goal::select('id', 'name')->where('id', $goalID)->first();
        if ($goal) {
            $this->goalID = $goal->id;
            $this->goalName = $goal->name;
        }

        Flux::modal('edit')->show();
    }

    public function updateGoal()
    {
        $committee = Committee::select('id')->where('token', $this->token)->first();

        $this->validate([
            'goalName' => 'required',
        ]);

        Goal::where('id', $this->goalID)->update([
            'name' => $this->goalName,
        ]);

        Flux::modal('edit')->close();
        return redirect()->back();
    }
}
