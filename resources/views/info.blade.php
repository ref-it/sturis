<flux:modal.trigger name="info">
    <flux:button
        variant="ghost"
        icon="info"
        title="{{ __('messages.about') }} {{ config('app.name') }} &hellip;"
        class="-mr-4!"
    />
</flux:modal>

<flux:modal name="info" class="max-w-lg">
    <flux:heading size="lg" class="modal-header">{{ config('app.name') }}</flux:heading>
    <div class="text-center">
        <h3 class="text-xl font-bold text-zinc-800 dark:text-white">{{ config('app.name') }} <span class="font-normal ml-1"> 2.0.0</span></h3>
        <div class="mt-2">
            <p class="text-zinc-800 dark:text-white hyphens-none"><b>Stu</b>dentisches <b>R</b>ats<b>I</b>nformations<b>S</b>ystem</p>
        </div>
        <div class="mt-6">
            <p class="text-zinc-800 dark:text-white mb-2">&copy; 2018 &ndash; 2025 Michael Gnehr, Martin Schlobach</p>
            <p class="text-zinc-800 dark:text-white mb-2">&copy; 2026  Marc Schlagenhauf</p>
            <p class="text-zinc-800 dark:text-white">{{ __('messages.licensedUnder') }} <flux:link href="https://www.gnu.org/licenses/agpl-3.0.txt" target="_blank" rel="noopener noreferrer">{{ __('messages.license') }}</flux:link>.</p>
        </div>
        {{-- <div class="space-y-2 bg-zinc-100 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 -mx-6 -mb-6 px-6 py-4 mt-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <flux:button size="sm" icon="external-link" href="{{ route('imprint') }}" target="_blank">{{ __('common.imprint') }}</flux:button>
                <flux:button size="sm" icon="external-link" href="{{ route('privacy') }}" target="_blank">{{ __('common.privacyPolicy') }}</flux:button>
                <flux:button size="sm" icon="external-link" href="{{ route('accessibility') }}" target="_blank">{{ __('common.accessibility') }}</flux:button>
            </div>
        </div> --}}
        {{-- <div class="space-y-2 bg-zinc-100 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 -mx-6 -mb-6 px-6 py-4 mt-6">
            <div class="flex flex-wrap gap-2 justify-center">
                <flux:button size="sm" icon="external-link" href="{{ route('documentation') }}" target="_blank">{{ __('common.documentation') }}</flux:button>
                <flux:button size="sm" icon="external-link" href="{{ route('source-code') }}" target="_blank">{{ __('common.sourceCode') }}</flux:button>
                <flux:button size="sm" icon="external-link" href="{{ route('translate') }}" target="_blank">{{ __('common.translate') }}</flux:button>
            </div>
        </div> --}}
    </div>
</flux:modal>