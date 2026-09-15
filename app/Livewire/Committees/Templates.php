<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use App\Models\Template;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Templates extends Component
{
    use WithPagination;

    #[Locked]
    public string $token = "";

    public string $type = "dokuwiki";
    public string $templateText = "";
    public $templateFile;

    public function mount($committee)
    {
        $this->token = $committee;
    }

    public function render()
    {
        $committee = Committee::where('token', $this->token)->first();

        $templates = Template::join('committees', 'committees.id', '=', 'templates.committee')
            ->select(
                'templates.id',
                'committees.name as committeeName',
                'committees.short_name as committeeNameShort',
            )
            ->orderBy('committees.name')
            ->orderBy('templates.type')
            ->paginate(10);

        $types = [
            [ 'id' => 'dokuwiki', 'title' => 'DokuWiki' ],
            [ 'id' => 'tex', 'title' => 'TeX' ],
            [ 'id' => 'odt', 'title' => 'OpenDocument (LibreOffice)' ],
            [ 'id' => 'docx', 'title' => 'OOXML (Word)' ],
        ];

        return view('livewire.committees.templates', [
            'committee' => $committee,
            'templates' => $templates,
            'types' => $types,
        ]);
    }

    public function removeFile()
    {
        $this->templateFile->delete();
        $this->templateFile = null;
    }

    public function clearModalFields()
    {
        $this->type = 'dokuwiki';
        $this->templateText = '';
        $this->templateFile = null;

    }

    public function openAddModal()
    {
        $this->clearModalFields();
        Flux::modal('new')->show();
    }

    public function addTemplate()
    {
        $this->validate([
            'type' => 'required',
        ]);

        if ($this->type === 'dokuwiki' || $this->type === 'tex') {
            $this->validate([
                'templateText' => 'required|string',
            ]);
        } elseif ($this->type === 'odt') {
            $this->validate([
                'templateFile' => 'required|mimes:odt',
            ]);
        } elseif ($this->type === 'docx') {
            $this->validate([
                'templateFile' => 'required|mimes:docx',
            ]);
        }

        $existingTemplates = Template::where('committee', $this->committee)
            ->where('type', $this->type)
            ->get();

        if ($existingTemplates === null) {
            $committee = Committee::where('id', $this->committee)->first();

            switch ($this->type) {
                case 'docx':
                    try {
                        $filename = $committee->token . '_' . $this->type . '.docx';
                        $this->templateFile->storeAs('templates', $filename);
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    break;
                
                case 'dokuwiki':
                    try {
                        $filename = $committee->token . '_' . $this->type . '.txt';
                        Storage::put('templates/' . $filename, $this->templateText);
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    break;

                case 'odt':
                    try {
                        $filename = $committee->token . '_' . $this->type . '.odt';
                        $this->templateFile->storeAs('templates', $filename);
                    } catch (\Exception $e) {
                        dd($e);
                    }
                    break;

                case 'tex':
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
