<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPostnummerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-postnummer';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasConfigFile()
            ->hasMigration('create_postnummer_table');
    }

    public function packageRegistered(): void
    {
        $this->app->register(FilamentPostnummerPluginServiceProvider::class);
    }
}
