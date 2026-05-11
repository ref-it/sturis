<div class="p-6 sm:px-8 space-y-6">
    <div class="space-y-4 flex-1">
        <flux:heading size="xl">{{ __('messages.addTerm') }}</flux:heading>
    </div>
    <div class="space-y-6">
        <flux:field>
            <flux:label>{{ __('messages.termNumber') }}</flux:label>
            <flux:input type="number" wire:model="number" />
            <flux:error name="number" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('messages.termStart') }}</flux:label>
            <flux:input type="date" wire:model="start" />
            <flux:error name="start" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('messages.termEnd') }}</flux:label>
            <flux:input type="date" wire:model="end" />
            <flux:error name="end" />
        </flux:field>
        <div class="flex justify-end">
            <flux:button variant="primary" icon="save" wire:click="save">{{ __('messages.save') }}</flux:button>
        </div>
    </div>
</div>
