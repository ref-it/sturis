<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Component;

class Dashboard extends Component
{
    #[Locked]
    public string $committee = "";

    public function mount($committee)
    {
        $this->committee = $committee;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
