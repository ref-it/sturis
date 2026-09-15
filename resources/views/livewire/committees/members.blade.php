<div class="flex flex-col h-full">
    <div class="flex flex-col h-full">
        <x-edit-committee-navbar :committeeToken="$token" :committeeName="$committee->name" />

        <div class="flex-1 overflow-y-auto">
            <div class="p-6 sm:p-8 space-y-6">
                <flux:fieldset>
                    <legend class="flex">
                        <span>{{ __('messages.members') }}</span>
                        <div class="ml-auto">
                            <flux:modal.trigger name="new">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="plus"
                                    title="{{ __('messages.addMember') }}"
                                    class="-my-2 top-1"
                                />
                            </flux:modal.trigger>
                        </div>
                    </legend>
                    <div class="p-4 space-y-6">
                        @if($groups)
                            @foreach($groups as $index => $g)
                                <flux:fieldset>
                                    <legend>{{ $g->name }}</legend>
                                    <div class="p-4">
                                        @if(count($membersElected[$index]) > 0)
                                            <ul class="divide-y divide-zinc-200 dark:divide-zinc-700 -my-3">
                                                @foreach($membersElected[$index] as $member)
                                                    <x-member :member="$member" :suspendable="true" />
                                                @endforeach
                                            </ul>
                                        @else
                                            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noMembers') }}" />
                                        @endif
                                    </div>
                                </flux:fieldset>
                            @endforeach
                        @else
                            @if(count($membersElected) > 0)
                                <ul class="divide-y divide-zinc-200 dark:divide-zinc-700 -my-3">
                                    @foreach($membersElected[$index] as $member)
                                        <x-member :member="$member" :suspendable="true" />
                                    @endforeach
                                </ul>
                            @else
                                <flux:callout variant="warning" icon="info" heading="{{ __('messages.noMembers') }}" />
                            @endif
                        @endif
                    </div>
                </flux:fieldset>
                
                <flux:fieldset>
                    <legend class="flex">
                        <span>{{ __('messages.activeMembers') }}</span>
                        <div class="ml-auto">
                            <flux:modal.trigger name="new">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="plus"
                                    title="{{ __('messages.addMember') }}"
                                    class="-my-2 top-1"
                                />
                            </flux:modal.trigger>
                        </div>
                    </legend>
                    <div class="p-4">
                        @if(count($membersActive) > 0)
                            <ul class="divide-y divide-zinc-200 dark:divide-zinc-700 -my-3">
                                @foreach($membersActive as $member)
                                    <x-member :member="$member" :suspendable="false" />
                                @endforeach
                            </ul>
                        @else
                            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noMembers') }}" />
                        @endif
                    </div>
                </flux:fieldset>

                <flux:fieldset>
                    <legend class="flex">
                        <span>{{ __('messages.staff') }}</span>
                        <div class="ml-auto">
                            <flux:modal.trigger name="new">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="plus"
                                    title="{{ __('messages.addMember') }}"
                                    class="-my-2 top-1"
                                />
                            </flux:modal.trigger>
                        </div>
                    </legend>
                    <div class="p-4">
                        @if(count($membersStaff) > 0)
                            <ul class="divide-y divide-zinc-200 dark:divide-zinc-700 -my-3">
                                @foreach($membersStaff as $member)
                                    <x-member :member="$member" :suspendable="false" />
                                @endforeach
                            </ul>
                        @else
                            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noMembers') }}" />
                        @endif
                    </div>
                </flux:fieldset>
            </div>
        </div>
    </div>

    <flux:modal name="new" class="md:w-full flex flex-col">
        <flux:heading size="lg" class="modal-header">{{ __('messages.addMember') }}</flux:heading>
        <div class="flex-1 overflow-y-auto -m-6 p-6">
            @include('components.edit-member-modal')
            <div class="flex justify-end mt-8">
                <flux:button variant="primary" icon="save" wire:click="addMember">{{ __('messages.save') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="edit" class="md:w-full flex flex-col">
        <flux:heading size="lg" class="modal-header">{{ __('messages.editMember') }}</flux:heading>
        <div class="flex-1 overflow-y-auto -m-6 p-6">
            @include('components.edit-member-modal')
            <div class="flex justify-end mt-8">
                <flux:button variant="primary" icon="save" wire:click="updateMember">{{ __('messages.save') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
