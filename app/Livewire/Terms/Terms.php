<?php

namespace App\Livewire\Terms;

use App\Models\Term;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Terms extends Component
{
    use WithPagination;
    
    public function render()
    {
        $terms = Term::orderBy('number', 'desc')->paginate(10);

        return view('livewire.terms.terms', [
            'terms' => $terms,
        ]);
    }
}
