<div class="p-6 sm:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="space-y-4 flex-1">
            <flux:heading size="xl">{{ __('messages.departments') }}</flux:heading>
        </div>
        <div>
            <flux:button
                variant="primary"
                icon="plus"
                wire:navigate
                href="{{ route('department.new') }}"
            >
                {{ __('messages.addDepartment') }}
            </flux:button>
        </div>
    </div>
    <div>
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
    </div>
    <div>
        @if(count($departments) > 0)
            <flux:table class="mb-0">
                <flux:table.columns>
                    <flux:table.column>{{ __('messages.name') }}</flux:table.column>
                    <flux:table.column><span class="sr-only">{{ __('messages.options') }}</span></flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($departments as $d)
                        <flux:table.row>
                            <flux:table.cell>{{ $d->name }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex justify-end">
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        icon="pencil"
                                        wire:navigate
                                        href="{{ route('department.edit', ['id' => $d->id]) }}"
                                    >
                                        {{ __('messages.edit') }}
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="pagination">
                <flux:pagination :paginator="$departments" />
            </div>
        @else
            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noDepartments') }}" />
        @endif
    </div>
</div>
