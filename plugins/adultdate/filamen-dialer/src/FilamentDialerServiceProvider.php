<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentDialerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-dialer';

    public static string $viewNamespace = 'filament-dialer';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasViews(self::$viewNamespace);
    }

    public function packageBooted(): void
    {
        // Register Livewire Components
        Livewire::component('filament-dialer.phone-dialer-sidebar', \AdultDate\FilamentDialer\Livewire\PhoneDialerSidebar::class);
        Livewire::component('filament-dialer.phone-icon-button', \AdultDate\FilamentDialer\Livewire\PhoneIconButton::class);
    }
}
