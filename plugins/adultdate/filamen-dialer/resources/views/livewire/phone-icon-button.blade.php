<x-filament::icon-button
    icon="heroicon-o-phone"
    tooltip="Phone Dialer"
    x-on:click.prevent="$dispatch('open-modal', { id: 'phone-dialer-sidebar' })"
    wire:key="phone-icon-button"
/>
