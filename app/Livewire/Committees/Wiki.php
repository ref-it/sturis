<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.start')]
class Wiki extends Component
{
    #[Locked]
    public string $token = "";


    public bool $minutesInWiki = false;

    public bool $minutesSeparatedByYear = false;
    public bool $minutesSeparatedByTerm = false;
    
    public bool $wikiInternalMinutes = false;

    public ?string $wikiPathInternal = null;
    public ?string $wikiPathDraft = null;
    public ?string $wikiPathPublic = null;

    public function mount($committee)
    {
        $this->token = $committee;
    }

    public function render()
    {
        $committee = Committee::where('token', $this->token)->first();

        return view('livewire.committees.wiki', [
            'committee' => $committee,
        ]);
    }
}
