<div class="flex flex-col w-full h-full p-6 sm:px-8">
    <div class="space-y-8">
        @if(count($committees) > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($committees as $c)
                    <a
                        class="flex flex-col dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md p-3 shadow-xs hover:ring-2 focus:ring-2 ring-(--color-accent-content)"
                        wire:navigate
                        href="{{ route('meetings', ['committee' => $c->token]) }}"
                        aria-label="{{ $c->name }}"
                    >
                        <div class="mx-auto mb-3"><img src="/favicon.svg" class="w-[3rem]" /></div>
                        <div class="mx-auto">{{ $c->name }}</div>
                    </a>
                @endforeach
            </div>
        @endif

        <flux:fieldset class="mb-6">
            <legend class="flex">
                <div class="flex-1">{{ __('messages.nextMeetings') }}</div>
                <div class="-my-1 -mr-2">
                    <flux:button
                        variant="ghost"
                        size="sm"
                        icon="calendar-days"
                        wire:navigate
                        href="{{ route('calendar') }}"
                    />
                </div>
            </legend>
            <div class="p-4">
                @if(count($meetings) > 0)
                    <ul class="space-y-4">
                        @foreach($meetings as $m)
                            <li>
                                <a
                                    wire:navigate
                                    href="{{ route('agenda', ['committee' => $m->committeeToken, 'meeting' => $m->id]) }}"
                                    class="block bg-zinc-100 dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-xs hover:ring-2 focus:ring-2 ring-(--color-accent-content)"
                                >
                                    <div class="px-4 pt-3 pb-2 font-medium">
                                        {{ $m->committeeName }}
                                        @if($m->committeeNameShort)
                                            ({{ $m->committeeNameShort }})
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-x-5 gap-y-2 px-4 pt-1 pb-3">
                                        <div class="flex gap-2 items-center">
                                            <flux:icon.calendar-days class="size-4" />
                                            <div>{{ $m->date }}</div>
                                        </div>
                                        <div class="flex gap-2 items-center">
                                            <flux:icon.clock class="size-4" />
                                            <div>{{ date('H:i', strtotime($m->time)) }}</div>
                                        </div>
                                        <div class="flex gap-2 items-center">
                                            <flux:icon.map-pin class="size-4" />
                                            <div>{{ $m->address }}</div>
                                        </div>
                                        <div class="flex gap-2 items-center">
                                            <flux:icon.door-closed class="size-4" />
                                            <div>{{ $m->room }}</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if(count($meetings) > 5)
                        <div class="pagination mt-4">
                            <flux:pagination :paginator="$meetings" />
                        </div>
                    @endif
                @else
                    <flux:callout variant="secondary" icon="info" heading="{{ __('messages.noUpcommingMeetings') }}" />
                @endif
            </div>
        </flux:fieldset>
    </div>
</div>
