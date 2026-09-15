<div>
    <flux:sidebar collapsible="mobile" class="w-[18rem]! p-0! flex flex-col gap-0! h-full grow bg-zinc-100 dark:bg-zinc-800">
        <flux:sidebar.header class="flex h-[4rem] px-6 shrink-0 items-center bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 border-r lg:border-r-0 border-r-zinc-300  dark:border-r-zinc-700 z-10">
            <a wire:navigate href="{{ route('select-committee') }}" class="h-full flex flex-1 items-center justify-start lg:justify-center">
                <span class="text-zinc-800 dark:text-white text-xl font-semibold">{{ config('app.name') }}</span>
            </a>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav class="grow overflow-y-auto border-r border-zinc-200 dark:border-zinc-700 px-6 py-4">
            <flux:sidebar.item
                icon="house"
                wire:navigate
                href="{{ route('select-committee') }}"
            >
                {{ __('messages.homepage') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="calendar-days"
                wire:navigate
                href="{{ route('calendar') }}"
            >
                {{ __('messages.calendar') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="network"
                wire:navigate
                href="{{ route('committees') }}"
                :current="request()->is('*/committees*')"
            >
                {{ __('messages.committees') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="calendar-range"
                wire:navigate
                href="{{ route('terms') }}"
                :current="request()->is('*/terms*')"
            >
                {{ __('messages.terms') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>
</div>