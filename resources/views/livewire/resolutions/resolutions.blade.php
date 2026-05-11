<div class="p-6 sm:px-8 sm:pb-8 space-y-8">
    <div class="flex">
        <div class="flex-1 space-y-4">
            <flux:heading size="xl">{{ __('messages.resolutions') }}</flux:heading>
        </div>
        <div class="flex gap-2">
            <flux:button
                variant="primary"
                icon="plus"
            >
                {{ __('messages.addResolution') }}
            </flux:button>
            <flux:button
                variant="primary"
                icon="download"
                wire:click="saveAsPdf"
            >
                {{ __('messages.saveAsPdf') }}
            </flux:button>
        </div>
    </div>

    <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden" x-data="{ expanded: false }">
        <button class="bg-zinc-100 dark:bg-zinc-800 px-4 py-3 font-semibold flex w-full cursor-pointer" @click="expanded = !expanded">
            <flux:icon name="funnel" class="mr-3 text-zinc-500 dark:text-white/80" />
            <span>{{ __('messages.filterResults') }}</span>
            <flux:icon name="chevron-down" class="ml-auto text-zinc-500 dark:text-white/80" x-show="!expanded" />
            <flux:icon name="chevron-up" class="ml-auto text-zinc-500 dark:text-white/80" x-show="expanded" />
        </button>

        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6 px-4 pt-3 pb-4 border-t border-zinc-200 dark:border-zinc-700" x-show="expanded" x-collapse>
            <div>
                <flux:input icon="search" label="{{ __('messages.number') }}" wire:model.live="number" clearable />
            </div>
            <div>
                <flux:input icon="search" label="{{ __('messages.text') }}" wire:model.live="text" clearable />
            </div>
            <div>
                <flux:pillbox multiple searchable clearable label="{{ __('messages.type') }}" wire:model.live="type">
                    @foreach($types as $t)
                        <flux:pillbox.option value="{{ $t['id'] }}">{{ $t['title'] }}</flux:pillbox.option>
                    @endforeach
                </flux:pillbox>
            </div>
            <div>
                <flux:select variant="listbox" searchable clearable label="{{ __('messages.term') }}" wire:model.live="term">
                    @foreach($terms as $t)
                        <flux:select.option value="{{ $t->number }}">{{ $t->number }}&nbsp; ({{ $t->start }} &ndash; {{ $t->end }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div>
                <flux:pillbox multiple searchable clearable label="{{ __('messages.meeting') }}" wire:model.live="meeting">
                    @foreach($meetings as $m)
                        <flux:pillbox.option value="{{ $m->id }}">{{ $m->date }}</flux:pillbox.option>
                    @endforeach
                </flux:pillbox>
            </div>
        </div>
    </div>

    <div>
        @if(count($resolutions) > 0)
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('messages.number') }}</flux:table.column>
                    <flux:table.column>{{ __('messages.text') }}</flux:table.column>
                    <flux:table.column>{{ __('messages.result') }}</flux:table.cloumn>
                    <flux:table.column>
                        <span class="sr-only">{{ __('messages.options') }}</span>
                    </flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($resolutions as $r)
                        <flux:table.row>
                            <flux:table.cell class="align-top">{{ $r->number }}</flux:table.cell>
                            <flux:table.cell class="align-top whitespace-normal">
                                <p>{{ $r->text }}</p>
                                @if($r->link)
                                    <p class="mt-2"><flux:link href="{{ $r->link }}" target="_blank" rel="noopener noreferrer">{{ $r->link }}</flux:link></p>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="flex flex-col gap-2">
                                <div class="flex gap-5">
                                    <div class="flex gap-2 items-center">
                                        <flux:icon.message-square-check
                                            class="size-4 text-green-600 dark:text-green-400"
                                            title="{{ __('messages.yes') }}"
                                            aria-hidden="true"
                                        />
                                        <span class="sr-only">{{ __('messages.yes') }}: </span>
                                        <div>{{ $r->yes }}</div>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <flux:icon.message-square-x
                                            class="size-4 text-red-600 dark:text-red-400"
                                            title="{{ __('messages.no') }}"
                                            aria-hidden="true"
                                        />
                                        <span class="sr-only">{{ __('messages.no') }}: </span>
                                        <div>{{ $r->no }}</div>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <flux:icon.message-square
                                            class="size-4 text-yellow-600 dark:text-yellow-200"
                                            title="{{ __('messages.abstention') }}"
                                            aria-hidden="true"
                                        />
                                        <span class="sr-only">{{ __('messages.abstention') }}: </span>
                                        <div>{{ $r->abstention }}</div>
                                    </div>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <flux:icon.message-square-text
                                        class="size-4 text-zinc-600 dark:text-zinc-400"
                                        aria-hidden="true"
                                    />
                                    <span class="sr-only">{{ __('messages.result') }}: </span>
                                    <div>{{ $r->result }}</div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex justify-end align-top">
                                    <flux:dropdown>
                                        <flux:button size="sm" icon="ellipsis-vertical" title="{{ __('messages.options') }}" />
                                        <flux:menu>
                                            <flux:menu.item icon="pencil">{{ __('messages.edit') }}</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            <div class="pagination">
                <flux:pagination :paginator="$resolutions" />
            </div>
        @else
            <flux:callout variant="warning" icon="info" heading="{{ __('messages.noResultsForSearch') }}" />
        @endif
    </div>
</div>