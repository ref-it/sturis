<div class="flex flex-col h-full">
    <div class="flex flex-col h-full">
        <x-edit-committee-navbar :committeeToken="$token" :committeeName="$committee->name" />

        <div class="flex-1 overflow-y-auto">
            <div class="p-6 sm:p-8">
                @if(count($departments) > 0)
                    <flux:table class="mb-0 -mt-4">
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
                                            wire:click="openEditModal({{ $d->id }})"
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

            <div class="mt-8">
                <flux:modal.trigger name="new">
                    <flux:button
                        variant="primary"
                        icon="plus"
                    >
                        {{ __('messages.addDepartment') }}
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
    </div>

    <flux:modal name="new" class="md:w-96">
        <flux:heading size="lg" class="modal-header">{{ __('messages.addDepartment') }}</flux:heading>
        <div class="space-y-6">
            <flux:input label="{{ __('messages.name') }}" wire:model="departmentName" />
            <div class="flex">
                <flux:spacer />
                <flux:button variant="primary" icon="save" wire:click="addDepartment">{{ __('messages.save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="edit" class="md:w-96">
        <flux:heading size="lg" class="modal-header">{{ __('messages.editDepartment') }}</flux:heading>
        <div class="space-y-6">
            <flux:input label="{{ __('messages.name') }}" wire:model="departmentName" />
            <div class="flex justify-end">
                <flux:button variant="primary" icon="save" wire:click="updateDepartment">{{ __('messages.save') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
