<?php

declare(strict_types=1);

namespace Andreia\FilamentUiSwitcher\Http\Middleware;

use Andreia\FilamentUiSwitcher\Support\UiPreferenceManager;
use Closure;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;

class ApplyUiPreferences
{
    public function handle(Request $request, Closure $next)
    {
        // At this point, session middleware has run, so we can access preferences
        if (Filament::isServing()) {
            $panel = Filament::getCurrentPanel();

            if ($panel) {
                // Load preferences (session is now available)
                $font = UiPreferenceManager::get('ui.font', config('ui-switcher.defaults.font', 'Inter'));
                $color = UiPreferenceManager::get('ui.color', config('ui-switcher.defaults.color', '#6366f1'));
                $layout = UiPreferenceManager::get('ui.layout', config('ui-switcher.defaults.layout', 'sidebar'));

                // Register color GLOBALLY using FilamentColor
                // This generates a proper Filament color palette and must run early
                FilamentColor::register([
                    'primary' => $color,
                ]);

                // $panel->colors([
                //     'primary' => $color,
                // ]);

                $panel->font($font);

                if ($layout === 'topbar') {
                    $panel->topNavigation();
                } elseif ($layout === 'sidebar-collapsed') {
                    $panel->sidebarCollapsibleOnDesktop();
                } elseif ($layout === 'sidebar-no-topbar') {
                    $panel->topbar(false);
                } else {
                    $panel->sidebarFullyCollapsibleOnDesktop(false);
                }
            }
        }

        return $next($request);
    }
}
