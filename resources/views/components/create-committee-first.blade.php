<flux:callout variant="warning" icon="circle-alert" inline>
    <flux:callout.heading>{{ __('messages.createCommitteeFirst') }}</flux:callout.heading>
    <x-slot name="actions">
        <flux:button icon="plus" wire:navigate href="{{ route('committee.new') }}">{{ __('messages.addCommittee') }}</flux:button>
    </x-slot>
</flux:callout>