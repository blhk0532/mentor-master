<?php

declare(strict_types=1);

namespace Andreia\FilamentUiSwitcher\Models\Traits;

trait HasUiPreferences
{
    public function getUiPreference(string $key, $default = null)
    {
        $prefs = $this->ui_preferences ?? [];

        return $prefs[$key] ?? $default;
    }

    public function setUiPreference(string $key, $value): void
    {
        $prefs = $this->ui_preferences ?? [];
        $prefs[$key] = $value;
        $this->ui_preferences = $prefs;

        $this->save();
    }
}
