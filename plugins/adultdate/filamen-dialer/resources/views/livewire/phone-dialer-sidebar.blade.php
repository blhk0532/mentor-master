<div class="h-full flex flex-col bg-white dark:bg-gray-800">
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Phone Dialer</h2>
        <button
            type="button"
            x-on:click.prevent="$dispatch('close-modal', { id: 'phone-dialer-sidebar' })"
            class="fi-topbar-action-button"
            title="Close"
        >
            <x-filament::icon icon="heroicon-o-x-circle" class="w-5 h-5" />
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4">
        @include('filament.partials.phone-dialer-inner')
    </div>
</div>
