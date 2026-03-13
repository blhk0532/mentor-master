<?php

declare(strict_types=1);

namespace Adultdate\FilamentShop;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Colors;
use Filament\Support\Facades\FilamentAsset;

class FilamentShop implements Plugin
{
    public function getId(): string
    {
        return 'filament-shop';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Theme::make('filament-shop', __DIR__.'/../resources/dist/filament-shop.css'),
        ]);

        $panel
            ->font('DM Sans')
            ->primaryColor(Colors\Color::Amber)
            ->secondaryColor(Colors\Color::Gray)
            ->warningColor(Colors\Color::Amber)
            ->dangerColor(Colors\Color::Rose)
            ->successColor(Colors\Color::Green)
            ->grayColor(Colors\Color::Gray)
            ->theme('filament-shop');
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
