<div class="space-y-6">
    <flux:field>
        <flux:label>{{ __('messages.type') }}</flux:label>
        <flux:select variant="listbox" searchable wire:model.live="type" class="mt-2">
            @foreach($types as $t)
                <flux:select.option value="{{ $t['id'] }}">{{ $t['title'] }}</flux:select.option>
            @endforeach
        </flux:select>
        <flux:error name="type" />
    </flux:field>

    @if($type === 'dokuwiki' || $type === 'tex')
        <flux:field>
            <flux:label>{{ __('messages.text') }}</flux:messages>
            <flux:textarea wire:model="templateText" class="md:h-[18rem] font-mono" />
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
