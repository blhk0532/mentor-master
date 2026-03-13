<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer;

use Adultdate\FilamentPostnummer\Resources\Postnummers\PostnummerResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentPostnummerPlugin implements Plugin
{
    public static function make(): static
    {
        return new self;
    }

    public function getId(): string
    {
        return 'filament-postnummer';
    }

    public function register(Panel $panel): void
    {
        // If the host app already defines a Postnummer resource in the
        // `App\Filament\Resources\Postnummers` namespace, prefer that
        // one so the app can override the plugin resource.
        $appResource = \App\Filament\Resources\Postnummers\PostnummerResource::class;

        if (! class_exists($appResource)) {
            $panel
                ->resources([
                    PostnummerResource::class,
                ]);
        }
    }

    public function boot(Panel $panel): void
    {
        // Plugin boot logic
    }
}
