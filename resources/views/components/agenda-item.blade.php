@if((isset($item->children) && count($item->children) > 0) || $item->editable || $item->agendaItemsAsChildren)
    @if($item->hasParent)
        <li class="w-full rounded-lg text-base! font-medium ring-1 ring-zinc-200 dark:ring-zinc-700 shadow-sm overflow-hidden cursor-grab" wire:sort:item="{{ $item->itemID }}" wire:key="{{ $item->itemID }}">
    @else
        <li class="w-full rounded-lg text-base! font-medium ring-1 ring-zinc-200 dark:ring-zinc-700 shadow-sm overflow-hidden">
    @endif
        <div class="flex gap-4 px-4 py-3 bg-zinc-100 dark:bg-zinc-800">
            <div class="flex-1 flex gap-3 text-base!">
                <div class="font-bold">{{ $preIndex . $index + 1 }}</div>
                <div>{{ $item->title }}</div>
            </div>
            <div class="-my-1 -mr-2 flex gap-2">
                @if($item->agendaItemsAsChildren)
                    <flux:tooltip content="{{ __('messages.addAgendaItem') }}">
                        <flux:button
                            variant="ghost"
                            size="sm"
                            icon="plus"
                            wire:navigate
                            href="{{ route('agenda-item.new', ['committee' => $committee, 'meeting' => $meeting, 'parentID' => $item->id]) }}"
                        />
                    </flux:tooltip>
                @endif
                @if($item->editable)
                    @if($item->contentExists)
                        <flux:tooltip content="{{ __('messages.view') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="eye"
                                wire:navigate
                                href="{{ route('agenda-item.view', ['committee' => $committee, 'meeting' => $meeting, 'id' => $item->itemID]) }}"
                            />
                        </flux:tooltip>
                    @endif
                    @if(!$item->contentExists)
                        <flux:tooltip content="{{ __('messages.edit') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="pencil"
                                wire:navigate
                                href="{{ route('agenda-item.new', ['committee' => $committee, 'meeting' => $meeting, 'id' => $item->id]) }}"
                            />
                        </flux:tooltip>
                    @endif
                    @if($nextMeeting && $item->contentExists)
                        <flux:tooltip content="{{ __('messages.postponeUntilNextWeek') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="step-forward"
                                wire:click="postponeItemUntilNextMeeting({{ $item->itemID }})"
                            />
                        </flux:tooltip>
                    @elseif($nextMeeting)
                        <flux:tooltip content="{{ __('messages.agendaItemHasNoContent') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="step-forward"
                                disabled
                            />
                        </flux:tooltip>
                    @else
                        <flux:tooltip content="{{ __('messages.scheduleNewMeetingFirst') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="step-forward"
                                disabled
                            />
                        </flux:tooltip>
                    @endif
                    @if($item->contentExists)
                        <flux:tooltip content="{{ __('messages.delete') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="trash-2"
                                wire:click="deleteItem({{ $item->itemID }})"
                            />
                        </flux:tooltip>
                    @else
                        <flux:tooltip content="{{ __('messages.agendaItemHasNoContent') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="trash-2"
                                disabled
                            />
                        </flux:tooltip>
                    @endif
                @endif
            </div>
        </div>
        @if($item->editable && $item->contentExists)
            <div class="flex flex-wrap gap-x-5 gap-y-2 px-4 pt-1 pb-3 bg-zinc-100 dark:bg-zinc-800 font-normal">
                <div class="flex gap-2 items-center">
                    <flux:icon.clock class="size-4" />
                    <span>{{ $item->expected_duration }} min</span>
                </div>
                <div class="flex gap-2 items-center">
                    <flux:icon.user class="size-4" />
                    <span>{{ implode(', ', isset($item->people) ? $item->people : []) }}</span>
                </div>
                @if(isset($item->goals) && count($item->goals) > 0)
                    <div class="flex gap-2 items-center">
                        <flux:icon.navigation class="size-4" />
                        <span>{{ implode(', ', isset($item->goals) ? $item->goals : []) }}</span>
                    </div>
                @endif
            </div>
            @if($item->guest || $item->internal)
                <div class="flex flex-wrap gap-2 px-4 pt-1 pb-3 bg-zinc-100 dark:bg-zinc-800 font-normal">
                    @if($item->guest)
                        <flux:badge icon="user-star">{{ __('messages.guest') }}</flux:badge>
                    @endif
                    @if($item->internal)
                        <flux:badge icon="door-closed-locked">{{ __('messages.internal') }}</flux:badge>
                    @endif
                </div>
            @endif
        @endif
        @if(isset($item->children) && count($item->children) > 0)
            @if($item->agendaItemsAsChildren)
                <ol class="space-y-4 p-4 border-t border-zinc-200 dark:border-zinc-700" wire:sort="sortFreeItems">
            @else
                <ol class="space-y-4 p-4 border-t border-zinc-200 dark:border-zinc-700">
            @endif
                @foreach($item->children as $i => $childItem)
                    @include('components.agenda-item', ['item' => $childItem, 'index' => $i, 'preIndex' => $preIndex  . $index + 1 . '.'])
                @endforeach
            </ol>
        @endif
    </li>
@else
    <li class="w-full px-4 py-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-base! font-medium ring-1 ring-zinc-200 dark:ring-zinc-700 shadow-sm">
        <div class="flex-1 flex gap-3">
            <div class="font-bold">{{ $preIndex . $index + 1 }}</div>
            <div>{{ $item->title }}</div>
        </div>
    </li>
@endif
