<?php

namespace App\Livewire\Terms;

use App\Models\Term;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.start')]
class EditTerm extends Component
{
    #[Locked]
    public $number;
    
    #[Validate('required|date')]
    public ?string $start;

    #[Validate('required|date')]
    public ?string $end;

    public function mount($number)
    {
        $this->number = $number;
    }

    public function render()
    {
        $term = Term::where('number', $this->number)->first();

        if (!$term) {
            abort(404);
        }

        $this->start = $term->start;
        $this->end = $term->end;

        return view('livewire.terms.edit-term');
    }

    public function save()
    {
        Term::where('number', $this->number)->update([
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $this->redirect('/terms', navigate: true);
    }
}
