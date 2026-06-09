<div class="p-6 sm:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="space-y-4 flex-1">
            <flux:heading size="xl">{{ __('messages.groups') }}</flux:heading>
        </div>
        @if(!$noCommittee)
            @can('admin')
                <div>
                    <flux:button
                        variant="primary"
                        icon="plus"
                        wire:navigate
                        href="{{ route('group.new') }}"
                    >
                        {{ __('messages.addGroup') }}
                    </flux:button>
                </div>
            @endcan
        @endif
    </div>
    <div>
        @if(!$noCommittee)
            <flux:field>
                <flux:label>{{ __('messages.committee') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model.live="committee">
                    @foreach($committees as $c)
                        <flux:select.option value="{{ $c->id }}">
                            {{ $c->name }}
                            @if($c->short_name)
                                ({{ $c->short_name }})
                            @endif
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        @else
            <x-create-committee-first />
        @endif
    </div>
    <div>
        @if(count($groups) > 0)
            <flux:table class="mb-0">
                <flux:table.columns>
                    <flux:table.column>{{ __('messages.name') }}</flux:table.column>
                    @can('admin')
                        <flux:table.column><span class="sr-only">{{ __('messages.options') }}</span></flux:table.column>
                    @endcan
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($groups as $group)
                        <flux:table.row>
                            <flux:table.cell>{{ $group->name }}</flux:table.cell>
                            @can('admin')
                                <flux:table.cell>
                                    <div class="flex justify-end">
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            icon="pencil"
                                            wire:navigate
                                            href="{{ route('group.edit', ['id' => $group->id]) }}"
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

            <div class="pagination">
                <flux:pagination :paginator="$groups" />
            </div>
        @else
            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noGroups') }}" />
        @endif
    </div>
</div>
