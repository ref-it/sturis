<?php

namespace App\Services\Pdf;

use Illuminate\Support\Collection;

/**
 * The resolutions of a committee, for the selected term, meetings and types.
 */
class ResolutionsPdf extends Document
{
    /**
     * @param  Collection<int, object>  $resolutions
     * @param  Collection<int, object>  $meetings  All meetings that can be selected.
     * @param  array<int, int>  $meeting  The selected meeting ids.
     * @param  array<int, array{id: int, title: string}>  $types  All resolution types.
     * @param  array<int, int>  $type  The selected type ids.
     */
    public function __construct(
        private string $committee,
        private Collection $resolutions,
        private Collection $meetings,
        private array $meeting,
        private array $types,
        private array $type,
        private ?string $term,
    ) {}

    protected function title(): string
    {
        return __('messages.resolutions').' - '.$this->committee;
    }

    protected function subject(): string
    {
        return __('messages.resolutions');
    }

    protected function markup(): string
    {
        return '<h1>'.e(__('messages.resolutions')).'</h1>'
            .$this->definitionTable([
                __('messages.committee') => $this->committee,
                __('messages.term') => $this->term,
                __('messages.meeting') => $this->meetings
                    ->whereIn('id', $this->meeting)
                    ->pluck('date')
                    ->implode(', '),
                __('messages.type') => collect($this->types)
                    ->whereIn('id', $this->type)
                    ->pluck('title')
                    ->implode(', '),
            ])
            .$this->table();
    }

    private function table(): string
    {
        $markup = '<table width="100%">'
            .'<thead><tr>'
            .'<th scope="col" class="number">'.e(__('messages.number')).'</th>'
            .'<th scope="col" class="text">'.e(__('messages.text')).'</th>'
            .'</tr></thead>'
            .'<tbody>';

        foreach ($this->resolutions as $resolution) {
            $markup .= '<tr>'
                .'<th scope="row" class="number">'.e($resolution->number).'</th>'
                .'<td class="text">'
                .'<p>'.e($resolution->text).'</p>'
                .'<p class="item-details">'.e($this->votes($resolution)).'</p>'
                .'</td>'
                .'</tr>';
        }

        return $markup.'</tbody></table>';
    }

    private function votes(object $resolution): string
    {
        return __('messages.yes').': '.$resolution->yes.', '
            .__('messages.no').': '.$resolution->no.', '
            .__('messages.abstention').': '.$resolution->abstention.', '
            .__('messages.result').': '.$resolution->result;
    }
}
