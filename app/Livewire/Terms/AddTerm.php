<?php

namespace App\Livewire\Terms;

use App\Models\Term;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.start')]
class AddTerm extends Component
{
    #[Validate('required')]
    public ?int $number;

    #[Validate('required|date')]
    public ?string $start;

    #[Validate('required|date')]
    public ?string $end;

    public function mount()
    {
        $highestTermNumber = (int) Term::max('number');
        $lastTermEnd = Term::max('end');
        $this->number = $highestTermNumber + 1;
        $this->start = date('Y-m-d', strtotime($lastTermEnd . '+1 day'));
        $this->end = date('Y-m-d', strtotime($this->start . '+1 year' . '-1 day'));
    }

    public function render()
    {
        return view('livewire.terms.add-term');
    }

    public function save()
    {
        $this->validate();

        Term::create([
            'number' => $this->number,
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $this->redirect('/terms', navigate: true);
    }
}
