<flux:field>
    <flux:label class="sr-only">{{ __('messages.active') }}</flux:label>
    <div class="block">
        <flux:switch wire:model="isActive" wire:change="switchCommitteeActive" />
    </div>
</flux:field>