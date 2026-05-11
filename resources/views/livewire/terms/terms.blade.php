<div class="p-6 sm:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="space-y-4 flex-1">
            <flux:heading size="xl">{{ __('messages.terms') }}</flux:heading>
        </div>
        @can('admin')
            <div>
                <flux:button
                    variant="primary"
                    icon="plus"
                    wire:navigate
                    href="{{ route('term.add') }}"
                >
                    {{ __('messages.addTerm') }}
                </flux:button>
            </div>
        @endcan
    </div>
    <div class="space-y-6">
        @if(count($terms) > 0)
            <flux:table class="mb-0">
                <flux:table.columns>
                    <flux:table.column>{{ __('messages.termNumber') }}</flux:table.column>
                    <flux:table.column>{{ __('messages.termStart') }}</flux:table.column>
                    <flux:table.column>{{ __('messages.termEnd') }}</flux:table.column>
                    @can('admin')
                        <flux:table.column><span class="sr-only">{{ __('messages.options') }}</span></flux:table.column>
                    @endcan
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($terms as $term)
                        <flux:table.row>
                            <flux:table.cell>{{ $term->number }}</flux:table.cell>
                            <flux:table.cell>{{ $term->start }}</flux:table.cell>
                            <flux:table.cell>{{ $term->end }}</flux:table.cell>
                            @can('admin')
                                <flux:table.cell>
                                    <div class="flex justify-end">
                                        <flux:button
                                            size="sm"
                                            variant="primary"
                                            icon="pencil"
                                            wire:navigate
                                            href="{{ route('term.edit', ['number' => $term->number]) }}"
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
                <flux:pagination :paginator="$terms" />
            </div>
        @else
            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noTerms') }}" />
        @endif
    </div>
</div>
