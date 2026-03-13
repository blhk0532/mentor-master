<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser;

use Adultdate\FilamentUser\Filament\Resources\User\UserResource as FilamentUserResource;
use Adultdate\FilamentUser\Filament\Resources\UserType\UserTypeResource as FilamentUserTypeResource;
use Filament\Filament;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUserServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-user';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }

    public function packageBooted(): void
    {
        // Register Filament resources when Filament is available
        if (class_exists(Filament::class)) {
            Filament::serving(function (): void {
                // Use Filament's registerResources API (preferred over low-level internals)
                Filament::registerResources([
                    FilamentUserResource::class,
                    FilamentUserTypeResource::class,
                ]);
            });
        }
    }
}
