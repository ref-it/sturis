<div class="p-6 sm:px-8 space-y-6">
    <div class="flex">
        <div class="flex-1 space-y-4">
            <flux:heading size="xl">{{ __('messages.meetings') }}</flux:heading>
        </div>
        <div>
            <flux:button
                variant="primary"
                icon="calendar-plus"
                href="{{ route('meeting.new', ['committee' => $committee]) }}"
            >
                {{ __('messages.scheduleMeeting') }}
            </flux:button>
        </div>
    </div>
    <div class="space-y-6">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('messages.date') }}</flux:table.column>
                <flux:table.column>{{ __('messages.time') }}</flux:table.column>
                <flux:table.column>{{ __('messages.address' ) }}</flux:table.column>
                <flux:table.column>{{ __('messages.room') }}</flux:table.cloumn>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($meetings as $m)
                    <flux:table.row>
                        <flux:table.cell>{{ $m->date }}</flux:table.cell>
                        <flux:table.cell>{{ date('H:i', strtotime($m->time)) }}</flux:table.cell>
                        <flux:table.cell>{{ $m->address }}</flux:table.cell>
                        <flux:table.cell>{{ $m->room }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2 justify-end">
                                @if(!$m->url_internal)
                                    <flux:button
                                        variant="primary"
                                        size="sm"
                                        icon="list-ordered"
                                        wire:navigate
                                        href="{{ route('agenda', ['committee' => $committee, 'meeting' => $m->id]) }}"
                                    >
                                        {{ __('messages.agenda') }}
                                    </flux:button>
                                @endif
                                @if($m->date >= date('Y-m-d'))
                                    <flux:button
                                        size="sm"
                                        icon="pencil"
                                        wire:navigate
                                        href="{{ route('meeting.edit', ['committee' => $committee, 'meeting' => $m->id]) }}"
                                    >
                                        {{ __('messages.edit') }}
                                    </flux:button>
                                @endif
                                @if($m->date >= date('Y-m-d'))
                                    <flux:button size="sm" icon="mail">{{ __('messages.invite') }}</flux:button>
                                @endif
                                @if($m->date >= date('Y-m-d'))
                                    <flux:button size="sm" icon="file-input">{{ __('messages.createMinutes') }}</flux:button>
                                @endif
                                @if($m->url_draft || $m->url_public)
                                    <flux:button
                                        variant="primary"
                                        size="sm"
                                        icon="scroll-text"
                                        wire:navigate
                                        href="{{ route('resolutions', ['committee' => $committee, 'meeting' => [$m->id]]) }}"
                                    >
                                        {{ __('messages.resolutions') }}
                                    </flux:button>
                                @endif
                                @if($m->url_internal && (!$m->url_public && !$m->url_draft))
                                    <flux:button size="sm" icon="file-badge">{{ __('messages.publish') }}</flux:button>
                                @endif
                                {{-- <flux:button size="sm" icon="scan-eye">{{ __('messages.investigate') }}</flux:button> --}}
                                
                                @if($m->url_internal || $m->url_draft || $m->url_public)
                                    <flux:dropdown>
                                        <flux:button size="sm" icon="file-text" icon:trailing="chevron-down">{{ __('messages.minutes') }}</flux:button>
                                        <flux:menu>
                                            @if($m->url_internal)
                                                <flux:menu.item
                                                    size="sm"
                                                    icon="file-lock"
                                                    href="{{ config('app.wiki.base_url') . '/' . config('app.wiki.minutes_base_internal') . ':' . $m->url_internal }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    {{ __('messages.internal') }}
                                                </flux:menu.item>
                                            @endif
                                            @if($m->url_public)
                                                <flux:menu.item
                                                    size="sm"
                                                    icon="file-check"
                                                    href="{{ config('app.wiki.base_url') . '/' . config('app.wiki.minutes_base_public') . ':' . $m->url_public }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    {{ __('messages.public') }}
                                                </flux:menu.item>
                                            @elseif($m->url_draft)
                                                <flux:menu.item
                                                    size="sm"
                                                    icon="file-pen"
                                                    href="{{ config('app.wiki.base_url') . '/' . config('app.wiki.minutes_base_draft') . ':' . $m->url_internal }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    {{ __('messages.draft') }}
                                                </flux:menu.item>
                                            @endif
                                        </flux:menu>
                                    </flux:dropdown>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        <div class="pagination -mt-6">
            <flux:pagination :paginator="$meetings" />
        </div>
    </div>
</div>