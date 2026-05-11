<div class="flex flex-col min-h-full p-6 sm:px-8 space-y-8">
    <div class="space-y-4">
        <flux:heading size="xl">{{ __('messages.addTemplate') }}</flux:heading>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="space-y-6">
            <flux:field>
                <flux:label>{{ __('messages.committee') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model="committee">
                    @foreach($committees as $c)
                        <flux:select.option value="{{ $c->id }}">{{ $c->name }} ({{ $c->short_name }})</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="committee" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('messages.type') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model.live="type">
                    @foreach($types as $t)
                        <flux:select.option value="{{ $t['id'] }}">{{ $t['title'] }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="type" />
            </flux:field>
        </div>
        <div class="space-y-6">
            @if($type === 'dokuwiki' || $type === 'tex')
                <flux:field>
                    <flux:label>{{ __('messages.text') }}</flux:messages>
                    <flux:textarea wire:model="templateText" />
                    <flux:error name="templateText" />
                </flux:field>
            @elseif($type === 'docx' || $type === 'odt')
                <flux:file-upload wire:model="templateFile" label="{{ __('messages.uploadFile') }}">
                    @if($type === 'docx')
                        <flux:file-upload.dropzone
                            heading="{{ __('messages.dropFileOrClickToBrowse') }}"
                            text="DOCX"
                        />
                    @elseif($type === 'odt')
                        <flux:file-upload.dropzone
                            heading="{{ __('messages.dropFileOrClickToBrowse') }}"
                            text="ODT"
                        />
                    @endif
                </flux:file-upload>
                <flux:error name="templateFile" />

                <div class="mt-3 flex flex-col gap-2">
                    @if($templateFile)
                        <flux:file-item
                            :heading="$templateFile->getClientOriginalName()"
                            :size="$templateFile->getSize()"
                        >
                            <x-slot name="actions">
                                <flux:file-item.remove wire:click="removeFile" aria-label="{{ __('messages.removeFile') . ': ' . $templateFile->getClientOriginalName() }}" />
                            </x-slot>
                        </flux:file-item>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="py-6 -mx-8 -mb-6 mt-auto px-8 flex items-center justify-end gap-x-4 border-t border-zinc-200 dark:border-zinc-900 bg-zinc-100 dark:bg-zinc-800">
        <flux:button icon="ban" wire:navigate href="{{ route('select-committee') }}">{{ __('messages.cancel') }}</flux:button>
        <flux:button variant="primary" icon="save" wire:click="save">{{ __('messages.save') }}</flux:button>
    </div>
</div>