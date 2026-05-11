<div class="p-6 sm:px-8 space-y-8">
    <div class="space-y-4">
        <flux:heading size="xl">{{ __('messages.editAgendaItem') }}</flux:heading>
    </div>
    <div class="grid sm:grid-cols-2 gap-6">
        <div class="space-y-6">

            <flux:field>
                <flux:label>{{ __('messages.title') }}</flux:label>
                <flux:input wire:model="title" :disabled="!$parent" />
                <flux:error name="title" />
            </flux:field>


            <flux:field>
                <flux:label>{{ __('messages.people') }}</flux:label>
                <flux:pillbox multiple searchable wire:model="people">
                    @foreach($users as $u)
                        <flux:pillbox.option value="{{ $u->id }}">{{ $u->name }}</flux:pillbox.option>
                    @endforeach
                </flux:pillbox>
                <flux:error name="people" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('messages.expectedDuration') }}</flux:label>
                <flux:input.group>
                    <flux:input type="number" wire:model="expectedDuration" />
                    <flux:input.group.suffix>{{ __('messages.minutes') }}</flux:input.group.suffix>
                </flux:input.group>
                <flux:error name="expectedDuration" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('messages.goal') }}</flux:label>
                <flux:pillbox multiple searchable wire:model="goal">
                    @foreach($goals as $g)
                        <flux:pillbox.option value="{{ $g->id }}">{{ $g->name }}</flux:pillbox.option>
                    @endforeach
                </flux:pillbox>
                <flux:error name="goals" />
            </flux:field>
            <div class="grid grid-cols-2 gap-6">
                <flux:field>
                    <flux:label class="block w-full">{{ __('messages.guestsExpected') }}</flux:label>
                    <flux:switch wire:model="externalGuests" />
                    <flux:error name="externalGuests" />
                </flux:field>
                <flux:field>
                    <flux:label class="block w-full">{{ __('messages.internal') }}</flux:label>
                    <flux:switch wire:model="internal" />
                    <flux:error name="internal" />
                </flux:field>
            </div>
        </div>
        <div class="space-y-6">
            <flux:editor
                wire:model="text"
                toolbar="bold italic underline | bullet ordered"
                label="{{ __('messages.text') }}" class="xl:h-[20.2rem]"
            />
        </div>
    </div>

    <div class="flex gap-2 justify-end">
        <flux:button icon="ban" wire:navigate href="{{ route('agenda', [$committee, $meeting]) }}">{{ __('messages.cancel') }}</flux:button>
        <flux:button variant="primary" icon="save" wire:click="save">{{ __('messages.save') }}</flux:button>
    </div>
</div>
