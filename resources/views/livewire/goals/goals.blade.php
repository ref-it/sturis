<div class="p-6 sm:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="space-y-4 flex-1">
            <flux:heading size="xl">{{ __('messages.goals') }}</flux:heading>
        </div>
        <div>
            <flux:button
                variant="primary"
                icon="plus"
                wire:navigate
                href="{{ route('goal.new') }}"
            >
                {{ __('messages.addGoal') }}
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
        @if(count($goals) > 0)
            <flux:table class="mb-0">
                <flux:table.columns>
                    <flux:table.column>{{ __('messages.name') }}</flux:table.column>
                    <flux:table.column><span class="sr-only">{{ __('messages.options') }}</span></flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($goals as $g)
                        <flux:table.row>
                            <flux:table.cell>{{ $g->name }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex justify-end">
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        icon="pencil"
                                        wire:navigate
                                        href="{{ route('goal.edit', ['id' => $g->id]) }}"
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
                <flux:pagination :paginator="$goals" />
            </div>
        @else
            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noGoals') }}" />
        @endif
    </div>
</div>
