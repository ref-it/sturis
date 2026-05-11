<?php

namespace App\Livewire\Templates;

use App\Models\Committee;
use App\Models\Template;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.start')]
class AddTemplate extends Component
{
    use WithFileUploads;

    #[Validate('required')]
    public ?string $committee = null;

    #[Validate('required')]
    public string $type = "";

    public string $templateText = "";

    public $templateFile;

    public function render()
    {
        $committees = Committee::orderBy('name')->get();
        $types = [
            [ 'id' => 'dokuwiki', 'title' => 'DokuWiki' ],
            [ 'id' => 'tex', 'title' => 'TeX' ],
            [ 'id' => 'odt', 'title' => 'OpenDocument (LibreOffice)' ],
            [ 'id' => 'docx', 'title' => 'OOXML (Word)' ],
        ];

        return view('livewire.templates.add-template', [
            'committees' => $committees,
            'types' => $types,
        ]);
    }

    public function removeFile()
    {
        $this->templateFile->delete();
        $this->templateFile = null;
    }

    public function save()
    {
        $this->validate();

        $existingTemplates = Template::where('committee', $this->committee)
            ->where('type', $this->type)
            ->get();

        if ($existingTemplates === null) {
            $committee = Committee::where('id', $this->committee)->first();

            switch ($this->type) {
                case 'docx':
                    Validator::make(
                        ['templateFile' => $this->templateFile],
                        ['templateFile' => 'required|mimes:docx']
                    )->validate();
                    try {
                        $filename = $committee->token . '_' . $this->type . '.docx';
                        $this->templateFile->storeAs('templates', $filename);
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    break;
                
                case 'dokuwiki':
                    Validator::make(
                        ['templateText' => $this->templateText],
                        ['templateText' => 'required|string']
                    )->validate();
                    try {
                        $filename = $committee->token . '_' . $this->type . '.txt';
                        Storage::put('templates/' . $filename, $this->templateText);
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    break;

                case 'odt':
                    Validator::make(
                        ['templateFile' => $this->templateFile],
                        ['templateFile' => 'required|mimes:odt']
                    )->validate();
                    try {
                        $filename = $committee->token . '_' . $this->type . '.odt';
                        $this->templateFile->storeAs('templates', $filename);
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    break;

                case 'tex':
                    Validator::make(
                        ['templateText' => $this->templateText],
                        ['templateText' => 'required|string']
                    )->validate();
                    try {
                        $filename = $committee->token . '_' . $this->type . '.tex';
                        Storage::put('templates/' . $filename, $this->templateText);
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    break;
            }

            Template::create([
                'committee' => $this->committee,
                'type' => $this->type,
                'filename' => $filename,
            ]);

            $this->redirectRoute('templates', ['committee' => $this->committee]);
        } else {
            Flux::toast(variant: 'success', text: __('messages.templateAlreadyExists'));
        }
    }
}
