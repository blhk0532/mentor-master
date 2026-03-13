<?php

declare(strict_types=1);

namespace Leandrocfe\FilamentApexCharts;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentApexChartsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @phpstan-ignore-next-line */
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-apex-charts';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
