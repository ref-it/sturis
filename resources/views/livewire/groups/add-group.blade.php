<div class="p-6 sm:px-8 sm:pb-8 space-y-8">
    <div class="space-y-4">
        <flux:heading size="xl">{{ __('messages.addGroup') }}</flux:heading>
    </div>
    <div class="space-y-6">
        <div class="space-y-6">
            <flux:field>
                <flux:label>{{ __('messages.committee') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model="committee">
                    @foreach($committees as $c)
                        <flux:select.option value="{{ $c->id }}">{{ $c->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="committee" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('messages.name') }}</flux:label>
                <flux:input wire:model="name" />
                <flux:error name="name" />
            </flux:field>
        </div>
        <div class="flex justify-end">
            <flux:button variant="primary" icon="save" wire:click="save">{{ __('messages.save') }}</flux:button>
        </div>
    </div>
</div>
