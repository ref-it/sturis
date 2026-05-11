<div class="grid lg:grid-cols-[2fr_1fr] h-full">
    <div class="p-6 sm:px-8 space-y-8">
        <div class="flex flex-col sm:flex-row gap-8">
            <div class="space-y-4 flex-1">
                <flux:heading size="xl">{{ $item->title }}</flux:heading>
                <div class="space-y-2">
                    <div class="flex flex-wrap gap-2">
                        <flux:badge icon="clock">{{ $item->expected_duration }} min</flux:badge>
                        <flux:badge icon="user">{{ implode(', ', $item->people) }}</flux:badge>
                        @if(count($item->goals) > 0)
                            <flux:badge icon="navigation">{{ implode(', ', $item->goals) }}</flux:badge>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($item->guest)
                            <flux:badge icon="user-star">{{ __('messages.guestsExpected') }}</flux:badge>
                        @endif
                        @if($item->internal)
                            <flux:badge icon="door-closed-locked">{{ __('messages.internal') }}</flux:badge>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <flux:button
                    variant="primary"
                    icon="pencil"
                    wire:navigate
                    href="{{ route('agenda-item.edit', ['committee' => $committee, 'meeting' => $meeting, 'id' => $id]) }}"
                >
                    {{ __('messages.edit') }}
                </flux:button>
            </div>
        </div>
        <div class="text">
            {!! $item->text !!}
        </div>
    </div>
    <div class="lg:overflow-y-auto border-t lg:border-l lg:border-t-0 border-zinc-200 dark:border-zinc-700 p-6 sm:px-8 space-y-8">
        <flux:tab.group>
            <flux:tabs scrollable scrollable:fade>
                <flux:tab name="motions" icon="clipboard">
                    {{ __('messages.motions') }}
                    <flux:badge size="sm" class="ml-1">{{ $motions->count() }}</flux:badge>
                </flux:tab>
                <flux:tab name="attachments" icon="paperclip">
                    {{ __('messages.attachments') }}
                    <flux:badge size="sm" class="ml-1">{{ $attachments->count() }}</flux:badge>
                </flux:tab>
            </flux:tabs>

            <flux:tab.panel name="motions">
                <div class="space-y-6">
                    @if(count($motions) > 0)
                        <ul class="space-y-4">
                            @foreach($motions as $m)
                                <li class="p-3 pl-4 flex gap-6 items-center bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                                    <span class="line-clamp-4">{{ $m->text }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <flux:callout variant="secondary" icon="info" heading="{{ __('messages.noMotions') }}" />
                    @endif
                    <flux:button
                        variant="primary"
                        icon="pencil"
                        wire:navigate
                        href="{{ route('motions', ['committee' => $committee, 'meeting' => $meeting, 'item' => $id]) }}"
                    >
                        {{ __('messages.edit') }}
                    </flux:button>
                </div>
            </flux:tab.panel>
            <flux:tab.panel name="attachments">
                <div class="space-y-6">
                    @if(count($attachments) > 0)
                        <ul>
                            @foreach($attachments as $a)
                                <li></li>
                            @endforeach
                        </ul>
                    @else
                        <flux:callout variant="secondary" icon="info" heading="{{ __('messages.noAttachments') }}" />
                    @endif
                    <flux:button
                        variant="primary"
                        icon="pencil"
                        wire:navigate
                        href="{{ route('attachments', ['committee' => $committee, 'meeting' => $meeting, 'item' => $id]) }}"
                    >
                        {{ __('messages.edit') }}
                    </flux:button>
                </div>
            </flux:tab.panel>
        </flux:tab.group>
    </div>
</div>
