<?php

namespace App\Livewire\Groups;

use App\Models\Committee;
use App\Models\Group;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Groups extends Component
{
    use WithPagination;

    #[Url]
    public string $committee = "";

    public bool $noCommittee = false;

    public function mount(Request $request)
    {
        if ($request->committee) {
            $this->committee = $request->committee;
        } else {
            $committee = Committee::orderBy('name')->first();
            if (!$committee) {
                $this->noCommittee = true;
                return;
            }
            $this->committee = $committee->id;
        }
    }

    public function render()
    {
        $committees = Committee::orderBy('name')->get();

        $groups = Group::orderBy('name')
            ->where('committee', $this->committee)
            ->paginate(10);

        return view('livewire.groups.groups', [
            'committees' => $committees,
            'groups' => $groups,
        ]);
    }
}
