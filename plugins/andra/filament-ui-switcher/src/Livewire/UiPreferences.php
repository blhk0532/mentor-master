<?php

declare(strict_types=1);

namespace Andreia\FilamentUiSwitcher\Livewire;

use Andreia\FilamentUiSwitcher\Support\UiPreferenceManager;
use Livewire\Component;

class UiPreferences extends Component
{
    public bool $open = false;

    public string $font = 'Inter';

    public string $layout = 'sidebar';

    public string $primaryColor = '#6366f1';

    public int $fontSize = 16;

    public string $density = 'default';

    public bool $hasModeSwitcher = false;

    public function mount(): void
    {
        // Load saved preferences
        $this->font = UiPreferenceManager::get('ui.font', 'Inter');
        $this->layout = UiPreferenceManager::get('ui.layout', 'sidebar');
        $this->primaryColor = UiPreferenceManager::get('ui.color', '#6366f1');
        $this->fontSize = UiPreferenceManager::get('ui.font_size', 16);
        $this->density = UiPreferenceManager::get('ui.density', 'default');
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;

        // Dispatch Filament modal events
        if ($this->open) {
            $this->dispatch('open-modal', id: 'ui-switcher-modal');
        } else {
            $this->dispatch('close-modal', id: 'ui-switcher-modal');
        }
    }

    public function closeModal(): void
    {
        $this->open = false;
        $this->dispatch('close-modal', id: 'ui-switcher-modal');
    }

    public function setFont(string $font): void
    {
        $this->font = $font;
        UiPreferenceManager::set('ui.font', $font);

        $this->dispatch('reload-page');
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
        UiPreferenceManager::set('ui.layout', $layout);

        $this->dispatch('reload-page');
    }

    public function setColor(string $color): void
    {
        $this->primaryColor = $color;
        UiPreferenceManager::set('ui.color', $color);

        $this->dispatch('reload-page');
    }

    public function setFontSize($size): void
    {
        $this->fontSize = (int) $size;
        UiPreferenceManager::set('ui.font_size', (int) $size);

        $this->dispatch('reload-page');
    }

    public function setDensity(string $density): void
    {
        $this->density = $density;
        UiPreferenceManager::set('ui.density', $density);

        $this->dispatch('reload-page');
    }

    /**
     * Get available fonts from config
     */
    public function getAvailableFontsProperty(): array
    {
        return config('ui-switcher.fonts', ['Inter', 'Poppins', 'Roboto']);
    }

    /**
     * Get UI icon from config
     */
    public function getIconProperty(): string
    {
        return config('ui-switcher.icon', 'heroicon-o-cog-6-tooth');
    }

    /**
     * Get custom colors from config
     */
    public function getCustomColorsProperty(): array
    {
        return config('ui-switcher.custom_colors', []);
    }

    /**
     * Get font size range from config
     */
    public function getFontSizeRangeProperty(): array
    {
        return config('ui-switcher.font_size_range', ['min' => 12, 'max' => 20]);
    }

    public function render()
    {
        return view('filament-ui-switcher::livewire.ui-switcher');
    }
}
