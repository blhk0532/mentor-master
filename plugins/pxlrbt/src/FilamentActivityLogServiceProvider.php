<?php

declare(strict_types=1);

namespace pxlrbt\FilamentActivityLog;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentActivityLogServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-activity-log';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasViews()
            ->hasTranslations();
    }
}
