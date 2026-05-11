<div class="p-6 sm:px-8 space-y-6">
    <div class="space-y-4 flex">
        <flux:heading size="xl" class="flex-1">{{ __('messages.templates') }}</flux:heading>
        <div class="flex flex-wrap gap-2">
            <flux:button
                variant="primary"
                icon="plus"
                wire:navigate
                href="{{ route('template.new') }}"
            >
                {{ __('messages.addTemplate') }}
            </flux:button>
        </div>
    </div>
    <div class="space-y-6">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('messages.committee') }}</flux:table.column>
                <flux:table.column>{{ __('messages.type') }}</flux:table.column>
                <flux:table.column>
                    <span class="sr-only">{{ __('messages.options') }}</span>
                </flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($templates as $t)
                    <flux:table.row>
                        <flux:table.cell>{{ $t->committeeName }} ({{ $committeeNameShort }})</flux:table.cell>
                        <flux:table.cell>{{ $t->type }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button size="sm" icon="ellipsis-vertical" title="{{ __('common.options')" />
                                <flux:menu>
                                    <flux:menu.item
                                        wire:navigate 
                                        href="{{ route('template.edit', ['id' => $t->id]) }}"
                                        icon="pencil"
                                        title="{{ __('common.edit') }}"
                                    >
                                        {{ __('common.edit') }}
                                    </flux:menu.item>
                                    <flux:menu.item
                                        variant="danger"
                                        icon="trash-2"
                                        title="{{ __('common.delete') }}"
                                    >
                                        {{ __('common.delete') }}
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>