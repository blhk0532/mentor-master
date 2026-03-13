<?php

declare(strict_types=1);

namespace Andreia\FilamentUiSwitcher;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class FilamentUiSwitcherPlugin implements Plugin
{
    private string $iconRenderHook = PanelsRenderHook::USER_MENU_BEFORE;

    private bool $hasModeSwitcher = false;

    public static function make(): static
    {
        return new self;
    }

    public function getId(): string
    {
        return 'filament-ui-switcher';
    }

    public function iconRenderHook(string $hook): static
    {
        $this->iconRenderHook = $hook;

        return $this;
    }

    public function withModeSwitcher(bool $condition = true): static
    {
        $this->hasModeSwitcher = $condition;

        return $this;
    }

    public function register(Panel $panel): void
    {
        // Register custom middleware to apply preferences after session is available
        $panel->middleware([
            Http\Middleware\ApplyUiPreferences::class,
        ], isPersistent: true);

        // Register plugin assets
        FilamentAsset::register([
            Css::make('ui-switcher-styles', __DIR__.'/../dist/ui-switcher.css'),
            Js::make('ui-switcher-scripts', __DIR__.'/../dist/ui-switcher.js'),
        ], package: 'andreia/filament-ui-switcher');

        // Add cog icon to configured render hook (default: USER_MENU_BEFORE)
        // Livewire component is registered in ServiceProvider, so it's available here
        // Pass the mode switcher configuration to the component
        $panel->renderHook(
            $this->iconRenderHook,
            fn (): string => Blade::render('@livewire(\'filament-ui-switcher\', [\'hasModeSwitcher\' => '.($this->hasModeSwitcher ? 'true' : 'false').'])'),
        );

        // Inject font size CSS
        $panel->renderHook(
            PanelsRenderHook::HEAD_END,
            function (): string {
                $fontSize = Support\UiPreferenceManager::get('ui.font_size', 16);

                return <<<HTML
                <style>
                    :root {
                        --font-size-base: {$fontSize}px;
                    }
                    html {
                        font-size: {$fontSize}px !important;
                    }
                </style>
                HTML;
            }
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
