<?php

declare(strict_types=1);

namespace Fahiem\FilamentPinpoint;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPinpointServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-pinpoint';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations();
    }
}
