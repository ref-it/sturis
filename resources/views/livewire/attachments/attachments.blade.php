<div class="p-6 sm:px-8 sm:pb-8 space-y-6">
    <div class="space-y-4">
        <flux:heading size="xl">{{ __('messages.attachments') }}</flux:heading>
    </div>
    @if(count($attachments) > 0)
        <ul class="space-y-6">
            @foreach($attachments as $a)
                <li class="flex items-center justify-between p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                    <div>
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $a->filename }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $a->created_at->format('M d, Y') }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <flux:callout variant="warning" icon="info" heading="{{ __('messages.noAttachments') }}" />
    @endif
    <flux:separator />
    <flux:fieldset class="space-y-6">
        <legend size="xl">{{ __('messages.addAttachment') }}</legend>
        <div>
            <form wire:submit="upload">
                <div class="p-4 md:grid grid-cols-2 gap-6">
                    <div>
                        <flux:file-upload wire:model="files" label="{{ __('messages.uploadFiles') }}" multiple>
                            <flux:file-upload.dropzone
                                heading="{{ __('messages.dropFilesOrClickToBrowse') }}"
                                text="PDF"
                            />
                        </flux:file-upload>
                        <flux:error name="files" />
                    </div>
                    <div class="mt-[1.86rem] flex flex-col gap-2">
                        @foreach($files as $index => $file)
                            <flux:file-item
                                :heading="$file->getClientOriginalName()"
                                :size="$file->getSize()"
                            >
                                <x-slot name="actions">
                                    <flux:file-item.remove
                                        wire:click="removeFile({{ $index }})"
                                        aria-label="{{ __('messages.removeFile') . ': ' . $file->getClientOriginalName() }}"
                                    />
                                </x-slot>
                            </flux:file-item>
                        @endforeach
                    </div>
                </div>
                <div class="p-4 bg-zinc-100 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 flex gap-2 justify-end">
                    <flux:button
                        variant="primary"
                        icon="upload"
                        type="submit"
                        :disabled="count($files) < 1"
                    >
                        {{ __('messages.upload') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:fieldset>
</div>
