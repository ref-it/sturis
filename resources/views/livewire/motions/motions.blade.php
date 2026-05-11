<div class="p-6 sm:px-8 sm:pb-8 space-y-6">
    <div class="space-y-4">
        <flux:heading size="xl">{{ __('messages.motions') }}</flux:heading>
    </div>
    @if(count($motions) > 0)
        <ul class="space-y-4">
            @foreach($motions as $m)
                <li class="p-3 pl-4 flex gap-6 items-center bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                    <div class="flex-1">{{ $m->text }}</div>
                    <div>
                        <flux:button size="sm" icon="trash-2" wire:click="delete({{ $m->id }})" />
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <flux:callout variant="warning" icon="info" heading="{{ __('messages.noMotions') }}" />
    @endif
    <flux:separator />
    <flux:fieldset class="space-y-6">
        <legend size="xl">{{ __('messages.addMotion') }}</legend>
        <div>
            <div class="p-4 space-y-6">
                <flux:field>
                    <flux:label>{{ __('messages.text') }}</flux:label>
                    <flux:textarea wire:model="text" class="h-[10rem]" />
                    <flux:error name="text" />
                </flux:field>
            </div>
            <div class="p-4 bg-zinc-100 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 flex gap-2 justify-end">
                <flux:button variant="primary" icon="save" wire:click="add">{{ __('messages.save') }}</flux:button>
            </div>
        </div>
    </flux:fieldset>
</div>
