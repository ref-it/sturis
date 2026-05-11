<div class="p-6 sm:px-8 space-y-6">
    <div class="space-y-4 flex">
        <flux:heading size="xl" class="flex-1">{{ __('messages.members') }}</flux:heading>
        <div class="flex flex-wrap gap-2">
            <flux:button variant="primary" icon="refresh-ccw">{{ __('messages.importMembers') }}</flux:button>
            <flux:button variant="primary" icon="plus" wire:navigate href="{{ route('member.add', ['committee' => $committee]) }}">{{ __('messages.addMember') }}</flux:button>
        </div>
    </div>
    <div class="space-y-6">
        <flux:fieldset>
            <legend>{{ __('messages.electedMembers') }}</legend>
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
        
        @if(count($membersRef) > 0)
            <flux:fieldset>
                <legend>{{ __('messages.activeMembers') }}</legend>
                <div class="p-4">
                    <ul class="divide-y divide-zinc-200 dark:divide-zinc-700 -my-3">
                        @foreach($membersRef as $member)
                            <x-member :member="$member" :suspendable="false" />
                        @endforeach
                    </ul>
                </div>
            </flux:fieldset>
        @endif

        @if(count($membersStaff) > 0)
            <flux:fieldset>
                <legend>{{ __('messages.staff') }}</legend>
                <div class="p-4">
                    <ul class="divide-y divide-zinc-200 dark:divide-zinc-700 -my-3">
                        @foreach($membersStaff as $member)
                            <x-member :member="$member" :suspendable="false" />
                        @endforeach
                    </ul>
                </div>
            </flux:fieldset>
        @endif
    </div>
</div>
