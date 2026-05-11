<?php

namespace App\Livewire\Motions;

use App\Models\Motion;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Motions extends Component
{
    #[Locked]
    public ?string $committee = null;

    #[Locked]
    public ?int $meeting = null;

    #[Locked]
    public ?int $agendaItem = null;

    #[Validate('required|string')]
    public string $text = "";

    public function mount($committee, $meeting, $item)
    {
        $this->committee = $committee;
        $this->meeting = $meeting;
        $this->agendaItem = $item;
    }

    public function render()
    {
        $motions = Motion::where('agenda_item', $this->agendaItem)->get();

        return view('livewire.motions.motions', [
            'motions' => $motions,
        ]);
    }

    public function add()
    {
        $this->validate();
        
        Motion::create([
            'agenda_item' => $this->agendaItem,
            'text' => $this->text,
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        Motion::where('id', $id)->delete();

        return redirect()->back();
    }
}
