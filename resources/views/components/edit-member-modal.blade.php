<div class="space-y-6">
    <div class="grid sm:grid-cols-2 gap-6">
        <flux:field>
            <flux:label>{{ __('messages.name') }}</flux:label>
            <flux:input wire:model="name" />
            <flux:error name="name" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('messages.emailAddress') }}</flux:label>
            <flux:input wire:model="email" />
            <flux:error name="email" />
        </flux:field>
    </div>
    <flux:field>
        <flux:label>{{ __('messages.departments') }}</flux:label>
        <flux:pillbox multiple searchable clearable wire:model="department">
            @foreach($departments as $d)
                <flux:pillbox.option value="{{ $d->id }}">{{ $d->name }}</flux:pillbox.option>
            @endforeach
        </flux:pillbox>
        <flux:error name="department" />
    </flux:field>
    <div class="grid sm:grid-cols-[auto_1fr] gap-x-12 gap-y-6">
        <flux:radio.group wire:model.live="flag" label="{{ __('messages.role') }}">
            <flux:radio value="elected" label="{{ __('messages.electedMember') }}" />
            <flux:radio value="active" label="{{ __('messages.activeMember') }}" />
            <flux:radio value="staff" label="{{ __('messages.staff') }}" />
        </flux:radio.group>
        @if($flag === 'elected')
            <flux:field>
                <flux:label>{{ __('messages.group') }}</flux:label>
                <flux:select variant="listbox" searchable clearable wire:model="group" class="mt-2">
                    @foreach($groups as $g)
                        <flux:select.option value="{{ $g->id }}">{{ $g->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="group" />
            </flux:field>
        @endif
    </div>
</div>
