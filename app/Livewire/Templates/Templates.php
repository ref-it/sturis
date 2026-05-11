<?php

namespace App\Livewire\Templates;

use App\Models\Template;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.start')]
class Templates extends Component
{
    use WithPagination;

    public function render()
    {
        $templates = Template::join('committees', 'committees.id', '=', 'templates.committee')
            ->select(
                'templates.id',
                'committees.name as committeeName',
                'committees.short_name as committeeNameShort',
            )
            ->orderBy('committees.name')
            ->orderBy('templates.type')
            ->paginate(10);

        return view('livewire.templates.templates', [
            'templates' => $templates,
        ]);
    }
}
