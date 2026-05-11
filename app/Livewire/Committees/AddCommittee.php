<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.start')]
class AddCommittee extends Component
{
    #[Validate('required')]
    public string $token = "";

    #[Validate('required')]
    public string $name = "";

    public ?string $shortName = null;

    #[Validate('required')]
    public int $defaultWeekday = 0;

    #[Validate('required')]
    public string $defaultTime = "19:00";

    #[Validate('required')]
    public string $defaultAddress = "";

    #[Validate('required')]
    public string $defaultRoom = "";

    public bool $isActive = true;
    public bool $minutesInWiki = false;
    public bool $minutesSeparatedByYear = false;
    public bool $minutesSeparatedByTerm = false;

    #[Validate('required')]
    public string $defaultLatitude = "";

    #[Validate('required')]
    public string $defaultLongitude = "";

    public bool $wikiInternalMinutes = false;

    public ?string $wikiPathInternal = null;
    public ?string $wikiPathDraft = null;
    public ?string $wikiPathPublic = null;

    public string $minutesStructure = "";

    public function mount()
    {
        $this->defaultLatitude = config('app.map.default_latitude');
        $this->defaultLongitude = config('app.map.default_longitude');
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

        return view('livewire.committees.add-committee', [
            'weekdays' => $weekdays,
        ]);
    }

    public function save()
    {
        $this->validate();

        $wikiInternalMinutes = false;
        if ($this->wikiPathInternal) {
            $wikiInternalMinutes = true;
        }

        Committee::create([
            'token' => $this->token,
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
            'wiki_internal_minutes' => $wikiInternalMinutes,
            'active' => $this->isActive,
            'minutes_in_wiki' => $this->minutesInWiki,
            'minutes_separated_by_year' => $this->minutesSeparatedByYear,
            'minutes_structure' => $this->minutesStructure,
        ]);

        Flux::toast(variant: 'success', text: trans('messages.committeeAdded'));
        $this->redirect('/committees', navigate: true);
    }
}
