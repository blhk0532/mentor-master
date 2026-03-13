<?php

declare(strict_types=1);

namespace Andreia\FilamentUiSwitcher\Support;

use Illuminate\Support\Facades\Auth;

class UiPreferenceManager
{
    public static function get(string $key, $default = null)
    {
        $value = null;

        if (config('ui-switcher.driver') === 'database' && Auth::check()) {
            $value = Auth::user()->getUiPreference($key, $default);
        } else {
            $value = session($key, $default);
        }

        return $value;
    }

    public static function set(string $key, $value): void
    {
        if (config('ui-switcher.driver') === 'database' && Auth::check()) {
            Auth::user()->setUiPreference($key, $value);
        } else {
            session()->put($key, $value);
        }
    }
}
