<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.start')]
class EditCommittee extends Component
{
    #[Locked]
    public string $token = "";

    #[Validate('required')]
    public string $name = "";

    public ?string $shortName = null;

    #[Validate('required')]
    public int $defaultWeekday = 1;

    #[Validate('required')]
    public string $defaultTime = "19:00";

    #[Validate('required')]
    public string $defaultAddress = "";

    #[Validate('required')]
    public string $defaultRoom = "";

    public bool $isActive = true;
    public bool $minutesSeparatedByYear = false;

    #[Validate('required')]
    public string $defaultLatitude = "";

    #[Validate('required')]
    public string $defaultLongitude = "";

    public bool $minutesInWiki = false;

    public bool $wikiInternalMinutes = false;

    public ?string $wikiPathInternal = null;
    public ?string $wikiPathDraft = null;
    public ?string $wikiPathPublic = null;

    public string $minutesStructure = "";

    public function mount($committee)
    {
        $this->token = $committee;
    }

    public function render()
    {
        $weekdays = [
            [ 'id' => 0, 'name' => trans('weekdays.monday') ],
            [ 'id' => 1, 'name' => trans('weekdays.tuesday') ],
            [ 'id' => 2, 'name' => trans('weekdays.wednesday') ],
            [ 'id' => 3, 'name' => trans('weekdays.thursday') ],
            [ 'id' => 4, 'name' => trans('weekdays.friday') ],
            [ 'id' => 5, 'name' => trans('weekdays.saturday') ],
            [ 'id' => 6, 'name' => trans('weekdays.sunday') ],
        ];

        $committee = Committee::where('token', $this->token)->first();

        if (!$committee) {
            abort(404);
        }

        $this->name = $committee->name;
        $this->shortName = $committee->short_name;
        $this->defaultWeekday = $committee->default_weekday;
        $this->defaultTime = $committee->default_time;
        $this->defaultAddress = $committee->default_address;
        $this->defaultRoom = $committee->default_room;
        $this->defaultLatitude = $committee->default_latitude;
        $this->defaultLongitude = $committee->default_longitude;
        $this->isActive = $committee->active;
        $this->wikiInternalMinutes = $committee->wiki_internal_minutes;
        $this->wikiPathInternal = $committee->wiki_path_internal;
        $this->wikiPathDraft = $committee->wiki_path_draft;
        $this->wikiPathPublic = $committee->wiki_path_public;
        $this->minutesSeparatedByYear = $committee->minutes_separated_by_year;
        $this->minutesStructure = $committee->minutes_structure;

        return view('livewire.committees.edit-committee', [
            'weekdays' => $weekdays,
        ]);
    }

    public function save()
    {
        $this->validate();

        Committee::where('token', $this->token)->update([
            'name' => $this->name,
            'short_name' => $this->shortName,
            'default_weekday' => $this->defaultWeekday,
            'default_time' => $this->defaultTime,
            'default_address' => $this->defaultAddress,
            'default_room' => $this->defaultRoom,
            'default_latitude' => $this->defaultLatitude,
            'default_longitude' => $this->defaultLongitude,
            'wiki_path_internal' => $this->wikiPathInternal,
            'wiki_path_draft' => $this->wikiPathDraft,
            'wiki_path_public' => $this->wikiPathPublic,
            'active' => $this->isActive,
            'minutes_separated_by_year' => $this->minutesSeparatedByYear,
            'minutes_structure' => $this->minutesStructure,
        ]);

        Flux::toast(variant: 'success', text: trans('messages.committeeUpdated'));
        $this->redirect('/committees', navigate: true);
    }
}
