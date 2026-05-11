<?php

namespace App\Livewire\Meetings;

use App\Models\Meeting;
use App\Models\Committee;
use Livewire\Component;
use Livewire\WithPagination;

class Meetings extends Component
{
    use WithPagination;

    public $committee;
    public $committeeID;

    public function mount($committee)
    {
        $this->committee = $committee;
        $committeeData = Committee::where('token', $this->committee)->first();
        $this->committeeID = $committeeData->id;
    }

    public function render()
    {
        $meetings = Meeting::where('committee', $this->committeeID)->orderBy('date', 'desc')->paginate(10);
        return view('livewire.meetings.meetings', [
            'meetings' => $meetings,
        ]);
    }
}
