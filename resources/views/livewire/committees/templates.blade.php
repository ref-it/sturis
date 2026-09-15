<div class="flex flex-col h-full">
    <div class="flex flex-col h-full">
        <x-edit-committee-navbar :committeeToken="$token" :committeeName="$committee->name" />

        <div class="flex-1 overflow-y-auto">
            <div class="p-6 sm:p-8">
                @if(count($templates) > 0)
                    <flux:table class="mb-0 -mt-4">
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
                @else
                    <flux:callout variant="warning" icon="info" heading="{{ __('messages.noTemplates') }}" />
                @endif
                <div class="mt-8">
                    <flux:button
                        variant="primary"
                        icon="plus"
                        wire:click="openAddModal"
                    >
                        {{ __('messages.addTemplate') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
    
    <flux:modal name="new" class="md:w-full">
        <flux:heading size="lg" class="modal-header">{{ __('messages.addTemplate') }}</flux:heading>
        <div class="space-y-6">
            @include('components.edit-template-modal')
            <div class="flex justify-end">
                <flux:button variant="primary" icon="save" wire:click="addGroup">{{ __('messages.save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="edit" class="md:w-full">
        <flux:heading size="lg" class="modal-header">{{ __('messages.editTemplate') }}</flux:heading>
        <div class="space-y-6">
            @include('components.edit-template-modal')
            <div class="flex justify-end">
                <flux:button variant="primary" icon="save" wire:click="updateGroup">{{ __('messages.save') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>