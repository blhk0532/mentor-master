<?php

declare(strict_types=1);

namespace Andreia\FilamentUiSwitcher;

use Andreia\FilamentUiSwitcher\Livewire\UiPreferences as UiPreferencesLivewire;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUiSwitcherServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-ui-switcher')
            ->hasConfigFile('ui-switcher')
            ->hasViews()
            ->hasTranslations()
            ->hasAssets();
    }

    public function packageRegistered(): void
    {
        // This runs during the package registration phase
    }

    public function packageBooted(): void
    {
        Livewire::component('filament-ui-switcher', UiPreferencesLivewire::class);
    }

    public function boot()
    {
        parent::boot();

        // Publishing migration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/add_ui_preferences_to_users_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_add_ui_preferences_to_users_table.php'),
            ], 'filament-ui-switcher-migrations');
        }
    }
}
