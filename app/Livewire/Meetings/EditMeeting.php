<?php

namespace App\Livewire\Meetings;

use App\Models\Committee;
use App\Models\CurrentMember;
use App\Models\Meeting;
use Livewire\Component;

class EditMeeting extends Component
{
    public $committee;
    public ?int $committeeID;
    public ?int $meetingID;

    public string $date = '1970-01-01';

    public ?string $time = '00:00';

    public ?string $address = "";

    public array $meetingChairs = [];

    public array $minuteTakers = [];

    public ?string $room = "";

    public string $latitude = "";

    public string $longitude = "";

    public string $minutesStructure = "";

    public function mount($committee, $meeting)
    {
        $this->committee = $committee;
        $this->meetingID = $meeting;

        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;

        $meetingData = Meeting::where('id', $meeting)->first();
        if ($meetingData) {
            $this->date = $meetingData->date;
            $this->time = $meetingData->time;
            $this->address = $meetingData->address;
            $this->room = $meetingData->room;
            $this->latitude = $meetingData->latitude;
            $this->longitude = $meetingData->longitude;
            $this->minutesStructure = $meetingData->minutes_structure;
            $this->meetingChairs = json_decode($meetingData->meeting_chairs, true) ?? [];
            $this->minuteTakers = json_decode($meetingData->minute_takers, true) ?? [];
        }
    }

    public function render()
    {
        $members = CurrentMember::where('committee', $this->committeeID)
            ->orderBy('name')
            ->get();

        return view('livewire.meetings.edit-meeting', [
            'members' => $members,
        ]);
    }

    public function save()
    {
        Meeting::where('id', $this->meetingID)->update([
            'date' => $this->date,
            'time' => $this->time,
            'meeting_chairs' => json_encode($this->meetingChairs),
            'minute_takers' => json_encode($this->minuteTakers),
            'address' => $this->address,
            'room' => $this->room,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'minutes_structure' => $this->minutesStructure,
        ]);

        $this->redirect('/' . $this->committee . '/meetings', navigate: true);
    }
}
