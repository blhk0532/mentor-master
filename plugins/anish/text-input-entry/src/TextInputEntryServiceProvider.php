<?php

namespace Anish\TextInputEntry;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TextInputEntryServiceProvider extends PackageServiceProvider
{
    public static string $name = 'text-input-entry';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasAssets();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('text-input-entry', __DIR__.'/../dist/text-input-entry.css'),
            Js::make('text-input-entry', __DIR__.'/../dist/text-input-entry.js'),
        ], package: 'anish/text-input-entry');
    }
}
