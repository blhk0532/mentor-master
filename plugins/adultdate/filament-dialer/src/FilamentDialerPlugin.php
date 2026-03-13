<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer;

use AdultDate\FilamentDialer\Pages\AutoDialerPage;
use AdultDate\FilamentDialer\Pages\PhoneDialerPage;
use AdultDate\FilamentDialer\Widgets\PhoneDialerWidget;
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
        $panel->pages([
            //    PhoneDialerPage::class,
            AutoDialerPage::class,
        ]);

        $panel->widgets([
            //    PhoneDialerWidget::class,
        ]);

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

        if ($this->showPhoneIcon) {
            $panel->renderHook(
                PanelsRenderHook::SIDEBAR_LOGO_AFTER,
                fn (): string => Blade::render('@livewire(\'filament-dialer.phone-icon-button\', [], key(\'phone-icon-button\'))')
            );
        }
        if ($this->showPhoneIcon) {
            $panel->renderHook(
                PanelsRenderHook::TOPBAR_LOGO_AFTER,
                fn (): string => Blade::render('@livewire(\'filament-dialer.phone-icon-button\', [], key(\'phone-icon-button\'))')
            );
        }

        if ($this->showSidebar) {
            $panel->renderHook(
                PanelsRenderHook::PAGE_END,
                fn (): string => Blade::render('
                    <x-filament::modal id="phone-dialer-sidebar" slide-over class="fi-modal-slide-over-left">
                        <x-slot name="heading">

                        </x-slot>
                        @livewire(\'filament-dialer.phone-dialer-sidebar\', [])
                    </x-filament::modal>
                ')
            );
        }
    }
}
