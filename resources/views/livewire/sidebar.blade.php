<div>
    <flux:sidebar collapsible="mobile" class="w-[18rem]! p-0! flex flex-col gap-0! h-full grow bg-zinc-100 dark:bg-zinc-800">
        <flux:sidebar.header class="flex h-[4rem] px-6 shrink-0 items-center bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 border-r lg:border-r-0 border-r-zinc-300  dark:border-r-zinc-700 z-10">
            <a wire:navigate href="{{ route('select-committee') }}" class="h-full flex flex-1 items-center justify-start lg:justify-center">
                <span class="text-zinc-800 dark:text-white text-xl font-semibold">{{ config('app.name') }}</span>
            </a>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        {{--<flux:sidebar.nav class="overflow-y-auto border-r border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
            <flux:sidebar.item
                icon="house"
                wire:navigate
                href="{{ route('select-committee') }}"
            >
                {{ __('messages.homepage') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>--}}

        <div class="px-3 py-4 border-r border-b border-zinc-200 dark:border-zinc-700">
            <flux:field>
                <flux:label class="sr-only">{{ __('messages.committee') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model="committee" wire:change="switchCommittee" placeholder="{{ __('messages.selectCommittee') }}">
                    @foreach($committees as $citem)
                        @if($citem->active)
                            <flux:select.option value="{{ $citem->token }}">{{ $citem->name }}</flux:select.option>
                        @else
                            @can('admin')
                                <flux:select.option value="{{ $citem->token }}">{{ $citem->name }}</flux:select.option>
                            @endcan
                        @endif
                    @endforeach
                </flux:select>
            </flux:field>
        </div>

        <flux:sidebar.nav class="grow overflow-y-auto border-r border-zinc-200 dark:border-zinc-700 px-6 py-4">
            <flux:sidebar.item
                icon="calendar-days"
                wire:navigate
                href="{{ route('meetings', ['committee' => $committee]) }}"
                :current="request()->is('*/meetings*')"
            >
                {{ __('messages.meetings') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="scroll-text"
                wire:navigate
                href="{{ route('resolutions', ['committee' => $committee]) }}"
            >
                {{ __('messages.resolutions') }}
            </flux:sidebar.item>
            {{--<flux:sidebar.item
                icon="list-todo"
                wire:navigate
                href="{{ route('todos', ['committee' => $committee]) }}"
            >
                {{ __('messages.todos') }}
            </flux:sidebar.item>--}}
            <flux:sidebar.item
                icon="users"
                wire:navigate
                href="{{ route('members', ['committee' => $committee]) }}"
            >
                {{ __('messages.members') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>
</div>