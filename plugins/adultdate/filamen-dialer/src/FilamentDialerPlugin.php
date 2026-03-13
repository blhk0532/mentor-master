<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class FilamentDialerPlugin implements Plugin
{
    private bool $showPhoneIcon = true;

    private bool $showSidebar = true;

    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-dialer';
    }

    public function register(Panel $panel): void
    {
        // Register render hooks for dialer integration
        $this->registerRenderHooks($panel);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function showPhoneIcon(bool $condition = true): static
    {
        $this->showPhoneIcon = $condition;

        return $this;
    }

    public function showSidebar(bool $condition = true): static
    {
        $this->showSidebar = $condition;

        return $this;
    }

    private function registerRenderHooks(Panel $panel): void
    {
        // Register phone icon button in global search before hook
        if ($this->showPhoneIcon) {
            $panel->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => Blade::render('@livewire(\'filament-dialer.phone-icon-button\', [], key(\'phone-icon-button\'))')
            );
        }

        // Register sidebar modal in page end
        if ($this->showSidebar) {
            $panel->renderHook(
                PanelsRenderHook::PAGE_END,
                fn (): string => Blade::render('
                    <div id="phone-dialer-sidebar-container" x-data="{ open: false }">
                        <div id="phone-dialer-sidebar" x-show="open" x-on:open-modal.window="if ($event.detail.id === \'phone-dialer-sidebar\') { open = true }" x-on:close-modal.window="if ($event.detail.id === \'phone-dialer-sidebar\') { open = false }" style="display: none;" class="fixed inset-0 z-50 overflow-hidden">
                            <div style="display:flex; justify-content:flex-end;" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="w-full h-full flex inset-0 bg-black/50 transition-opacity" x-on:click="open = false"></div>
                            <div style="width: 100%;max-width: 500px;" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full" class="fixed inset-y-0 right-0 z-50 w-full sm:w-[400px] md:w-[450px] lg:w-[500px] bg-white dark:bg-gray-800 shadow-xl overflow-hidden">
                                <div class="h-full phone-dialer-sidebar-modal-widget" style="min-height: 100%;max-width: 500px; overflow: hidden;">
                                    @livewire(\'filament-dialer.phone-dialer-sidebar\', [])
                                </div>
                            </div>
                        </div>
                    </div>
                ')
            );
        }
    }
}
