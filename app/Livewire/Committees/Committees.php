<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.start')]
class Committees extends Component
{
    public function render()
    {
        $committees = Committee::orderBy('name')->paginate(10);
        return view('livewire.committees.committees', [
            'committees' => $committees,
        ]);
    }
}
