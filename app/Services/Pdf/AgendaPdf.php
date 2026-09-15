<?php

namespace App\Services\Pdf;

use App\Models\Meeting;

/**
 * The agenda of a single meeting.
 */
class AgendaPdf extends Document
{
    /**
     * @param  array<int, object>  $items  The minutes structure, as nested items.
     */
    public function __construct(
        private string $committee,
        private Meeting $meeting,
        private array $items,
    ) {}

    protected function title(): string
    {
        return __('messages.agenda').' - '.$this->committee.' - '.$this->meeting->date;
    }

    protected function subject(): string
    {
        return __('messages.agenda');
    }

    protected function markup(): string
    {
        return '<h1>'.e(__('messages.agenda')).'</h1>'
            .$this->definitionTable([
                __('messages.committee') => $this->committee,
                __('messages.date') => $this->meeting->date.', '.$this->meeting->time,
                __('messages.address') => $this->meeting->address,
                __('messages.room') => $this->meeting->room,
                __('messages.meetingChairs') => $this->names($this->meeting->meeting_chairs),
                __('messages.minuteTakers') => $this->names($this->meeting->minute_takers),
            ])
            .$this->list($this->items);
    }

    /**
     * The agenda items as a nested list. The item number is part of the item
     * text rather than a list marker, because it carries the position in the
     * hierarchy ("2.1") and has to be read out with the title.
     *
     * @param  array<int, object>  $items
     */
    private function list(array $items, string $prefix = ''): string
    {
        if ($items === []) {
            return '';
        }

        $markup = '<ol>';

        foreach ($items as $index => $item) {
            $number = $prefix.($index + 1);

            $markup .= '<li>'
                .'<b>'.e($number).'</b> '.e($item->title)
                .$this->details($item)
                .$this->list($item->children ?? [], $number.'.')
                .'</li>';
        }

        return $markup.'</ol>';
    }

    /**
     * Duration, people, goals and flags of an item that has content of its own.
     */
    private function details(object $item): string
    {
        if (! $item->editable || ! $item->contentExists) {
            return '';
        }

        $details = [$item->expected_duration.' min'];

        foreach ([$item->people ?? [], $item->goals ?? []] as $list) {
            if ($list !== []) {
                $details[] = implode(', ', $list);
            }
        }

        if ($item->guest) {
            $details[] = __('messages.guest');
        }

        if ($item->internal) {
            $details[] = __('messages.internal');
        }

        return '<br /><span class="item-details">'.e(implode(' – ', $details)).'</span>';
    }

    /**
     * @param  ?string  $json  A JSON encoded list of names.
     */
    private function names(?string $json): string
    {
        return implode(', ', json_decode($json ?? '[]', true) ?? []);
    }
}
