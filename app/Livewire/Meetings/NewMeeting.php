<?php

namespace App\Livewire\Meetings;

use App\Models\Committee;
use App\Models\CurrentMember;
use App\Models\Meeting;
use Illuminate\Support\Str;
use Livewire\Component;

class NewMeeting extends Component
{
    public $committee;
    public ?int $committeeID;

    public string $date = '1970-01-01';

    public ?string $time = '00:00';

    public ?string $address = "";

    public array $meetingChairs = [];

    public array $minuteTakers = [];

    public ?string $room = "";

    public string $latitude = "";

    public string $longitude = "";

    public string $minutesStructure = "";

    public function mount($committee)
    {
        $this->committee = $committee;

        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;
        $this->date = date('Y-m-d', strtotime('next ' . jddayofweek($committeeData->default_weekday, 1)));
        $this->time = $committeeData->default_time;
        $this->address = $committeeData->default_address;
        $this->room = $committeeData->default_room;
        $this->latitude = $committeeData->default_latitude;
        $this->longitude = $committeeData->default_longitude;
        $this->minutesStructure = $committeeData->minutes_structure;
    }

    public function render()
    {
        $members = CurrentMember::where('committee', $this->committeeID)
            ->orderBy('name')
            ->get();

        return view('livewire.meetings.new-meeting', [
            'members' => $members,
        ]);
    }

    public function save()
    {
        Meeting::create([
            'committee' => $this->committeeID,
            'date' => $this->date,
            'name' => \Carbon\Carbon::parse($this->date)->format('d.m.Y'),
            'time' => $this->time,
            'meeting_chairs' => json_encode($this->meetingChairs),
            'minute_takers' => json_encode($this->minuteTakers),
            'address' => $this->address,
            'room' => $this->room,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'minutes_structure' => $this->minutesStructure,
            'uid' => Str::uuid(),
            'ignore_minutes' => false,
        ]);

        $this->redirect('/' . $this->committee . '/meetings', navigate: true);
    }
}
