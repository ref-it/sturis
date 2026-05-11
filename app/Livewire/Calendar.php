<?php

namespace App\Livewire;

use App\Models\Committee;
use App\Models\Meeting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.start')]
class Calendar extends Component
{
    public function render()
    {
        $meetings = Meeting::join('committees', 'committees.id', '=', 'meetings.committee')
            ->select(
                'meetings.id',
                'meetings.date',
                'meetings.time',
                'meetings.room',
                'committees.token as committeeToken',
                'committees.name as committeeName',
                'committees.short_name as committeeNameShort',
            )
            ->orderBy('meetings.date')
            ->get();
        
        $meetingsForCalendar = [];
        foreach ($meetings as $m) {
            if ($m->committeeNameShort) {
                $m->committeeName = $m->committeeNameShort;
            }
            
            $meetingsForCalendar[] = [
                'title' => $m->committeeName,
                'start' => $m->date . ' ' . $m->time,
                'url' => config('app.url') . '/' . $m->committeeToken . '/meetings/' . $m->id . '/agenda',
            ];
        }

        return view('livewire.calendar', [
            'meetings' => $meetingsForCalendar,
        ]);
    }
}
