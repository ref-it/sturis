<?php

namespace App\Livewire;

use App\Models\Committee;
use Flux\Flux;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SwitchCommitteeActive extends Component
{
    #[Locked]
    public string $token = "";

    public bool $isActive = false;

    public function mount($committee)
    {
        $committeeData = Committee::where('token', $committee)->first();
        if ($committeeData) {
            $this->token = $committeeData->token;
            $this->isActive = $committeeData->active;
        }
    }

    public function render()
    {
        return view('livewire.switch-committee-active');
    }

    public function switchCommitteeActive()
    {
        Committee::where('token', $this->token)->update([
            'active' => $this->isActive,
        ]);
        
        Flux::toast(variant: 'success', text: trans('messages.committeeUpdated'));
    }
}
