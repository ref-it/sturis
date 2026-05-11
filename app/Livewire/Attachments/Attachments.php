<?php

namespace App\Livewire\Attachments;

use App\Models\Attachment;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Attachments extends Component
{
    use WithFileUploads;
    
    #[Locked]
    public ?string $committee = null;

    #[Locked]
    public ?int $meeting = null;

    #[Locked]
    public ?int $agendaItem = null;

    #[Validate(['files.*' => 'file|mimes:pdf'])]
    public $files = [];

    public function render()
    {
        $attachments = Attachment::where('agenda_item', $this->agendaItem)->get();

        return view('livewire.attachments.attachments', [
            'attachments' => $attachments,
        ]);
    }

    public function removeFile($index)
    {
        $file = $this->files[$index];
        $file->delete();
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }

    public function upload()
    {
        $this->validate();
        
        foreach ($this->files as $file) {
            $file->store(path: 'attachments');
            Attachment::create([
                'agenda_item' => $this->agendaItem,
                'filename' => $file->getClientOriginalName(),
                'created_by' => auth()->user()->id,
            ]);
        }

        $this->files = [];

        return redirect()->back();
    }
}
