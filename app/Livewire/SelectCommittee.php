<?php

namespace App\Livewire;

use App\Models\Committee;
use App\Models\Meeting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class SelectCommittee extends Component
{
    use WithPagination;

    public function render()
    {
        $committees = Committee::where('active', true)->orderBy('name')->get();
        
        $meetings = Meeting::where('meetings.date', '>=', today())
            ->join('committees', 'committees.id', '=', 'meetings.committee')
            ->select(
                'meetings.id',
                'meetings.date',
                'meetings.time',
                'meetings.address',
                'meetings.room',
                'committees.token as committeeToken',
                'committees.name as committeeName',
                'committees.short_name as committeeNameShort',
            )
            ->orderBy('meetings.date')
            ->orderBy('meetings.time')
            ->paginate(5);

        return view('livewire.select-committee', [
            'committees' => $committees,
            'meetings' => $meetings,
        ]);
    }
}
