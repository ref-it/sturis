<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use App\Models\Group;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Groups extends Component
{
    use WithPagination;

    #[Locked]
    public string $token = "";

    public string $groupName = "";
    public ?int $groupID;

    public function mount($committee)
    {
        $this->token = $committee;
    }

    public function render()
    {
        $committee = Committee::where('token', $this->token)->first();
        $this->isActive = $committee->active;

        $groups = Group::orderBy('name')
            ->where('committee', $committee->id)
            ->paginate(10);

        return view('livewire.committees.groups', [
            'committee' => $committee,
            'groups' => $groups,
        ]);
    }

    public function addGroup()
    {
        $committee = Committee::select('id')->where('token', $this->token)->first();

        $this->validate([
            'groupName' => 'required',
        ]);

        Group::create([
            'committee' => $committee->id,
            'name' => $this->groupName,
        ]);

        Flux::modal('new')->close();
        return redirect()->back();
    }

    public function openEditModal($groupID)
    {
        $group = Group::select('id', 'name')->where('id', $groupID)->first();
        if ($group) {
            $this->groupID = $group->id;
            $this->groupName = $group->name;
        }

        Flux::modal('edit')->show();
    }

    public function updateGroup()
    {
        $committee = Committee::select('id')->where('token', $this->token)->first();

        $this->validate([
            'groupName' => 'required',
        ]);

        Group::where('id', $this->groupID)->update([
            'name' => $this->groupName,
        ]);

        Flux::modal('edit')->close();
        return redirect()->back();
    }
}
