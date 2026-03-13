<?php

declare(strict_types=1);

namespace JeffersonGoncalves\Filament\RefreshSidebar;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;

class RefreshSidebarPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'filament-refresh-sidebar';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(PanelsRenderHook::SCRIPTS_AFTER, fn (): View => view('filament-refresh-sidebar::scripts'));
    }

    public function boot(Panel $panel): void {}
}
