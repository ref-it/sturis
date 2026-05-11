<div class="p-6 sm:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="space-y-4 flex-1">
            <flux:heading size="xl">{{ __('messages.committees') }}</flux:heading>
        </div>
        @can('admin')
            <div>
                <flux:button
                    variant="primary"
                    icon="plus"
                    wire:navigate
                    href="{{ route('committee.new') }}"
                >
                    {{ __('messages.addCommittee') }}
                </flux:button>
            </div>
        @endcan
    </div>
    <div class="space-y-6">
        @if(count($committees) > 0)
            <flux:table class="mb-0">
                <flux:table.columns>
                    <flux:table.column>{{ __('messages.name') }}</flux:table.column>
                    <flux:table.column>{{ __('messages.shortName') }}</flux:table.column>
                    @can('admin')
                        <flux:table.column><span class="sr-only">{{ __('messages.options') }}</span></flux:table.column>
                    @endif
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($committees as $c)
                        <flux:table.row>
                            <flux:table.cell>
                                <flux:link wire:navigate href="{{ route('meetings', ['committee' => $c->token]) }}">{{ $c->name }}</flux:link>
                            </flux:table.cell>
                            <flux:table.cell>{{ $c->short_name }}</flux:table.cell>
                            @can('admin')
                                <flux:table.cell>
                                    <div class="flex justify-end">
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            icon="pencil"
                                            wire:navigate
                                            href="{{ route('committee.edit', ['committee' => $c->token]) }}"
                                        >
                                            {{ __('messages.edit') }}
                                        </flux:button>
                                    </div>
                                </flux:table.cell>
                            @endcan
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            <div class="pagination -mt-6">
                <flux:pagination :paginator="$committees" />
            </div>
        @else
            <flux:callout variant="warning" icon="information-circle" heading="Your account has been successfully created." />
        @endif
    </div>
</div>
