<li class="py-3 flex items-center">
    <div class="flex-1 flex flex-col gap-2">
        <div>
            {{ $member->name }}
        </div>
        @if(count($member->job) > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($member->job as $j)
                    <flux:badge size="sm">{{ $j }}</flux:badge>
                @endforeach
            </div>
        @endif
    </div>
    <div class="flex gap-2">
        <flux:dropdown>
            <flux:button size="sm" icon="ellipsis-vertical" />
            <flux:menu>
                @if($suspendable)
                    @if($member->flag_suspended)
                        <flux:menu.item
                            icon="play"
                            wire:click="toggleSuspended({{ $member->id }})"
                        >
                            {{ __('messages.active') }}
                        </flux:menu.item>
                    @else
                        <flux:menu.item
                            icon="pause"
                            wire:click="toggleSuspended({{ $member->id }})"
                        >
                            {{ __('messages.suspended') }}
                        </flux:menu.item>
                    @endif
                @endif

                <flux:menu.item
                    size="sm"
                    icon="pencil"
                    wire:click="openEditModal({{ $member->id }})"
                >
                    {{ __('messages.edit') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </div>
</li>