<div>
    <div class="p-6 sm:px-8 pb-0! flex gap-4 items-center mb-2">
        <div class="flex-1 flex gap-4 items-center">
            <flux:heading size="xl">{{ $committeeName }}</flux:heading>
            <div><flux:badge>{{ $committeeToken }}</flux:badge></div>
        </div>
        <div>
            <livewire:switch-committee-active :committee="$committeeToken" />
        </div>
    </div>

    <div class="border-b border-zinc-200 dark:border-zinc-700">
        <div class="mx-6 sm:mx-8">
            <div class="max-w-7xl mx-auto -mb-[1px] overflow-x-auto">
                <flux:navbar>
                    <flux:navbar.item wire:navigate href="{{ route('committee.edit', ['committee' => $committeeToken]) }}" icon="settings">{{ __('messages.general') }}</flux:navbar.item>
                    <flux:navbar.item wire:navigate href="{{ route('groups', ['committee' => $committeeToken]) }}" icon="chart-pie">{{ __('messages.groups') }}</flux:navbar.item>
                    <flux:navbar.item wire:navigate href="{{ route('departments', ['committee' => $committeeToken]) }}" icon="award">{{ __('messages.departments') }}</flux:navbar.item>
                    <flux:navbar.item wire:navigate href="{{ route('committee.members', ['committee' => $committeeToken]) }}" icon="users">{{ __('messages.members') }}</flux:navbar.item>
                    <flux:navbar.item wire:navigate href="{{ route('goals', ['committee' => $committeeToken]) }}" icon="navigation">{{ __('messages.goals') }}</flux:navbar.item>
                    <flux:navbar.item wire:navigate href="{{ route('templates', ['committee' => $committeeToken]) }}" icon="file-text">{{ __('messages.templates') }}</flux:navbar.item>
                    <flux:navbar.item wire:navigate href="{{ route('committee.wiki', ['committee' => $committeeToken]) }}" icon="notebook-text">{{ __('messages.wiki') }}</flux:navbar.item>
                </flux:navbar>
            </div>
        </div>
    </div>
</div>
