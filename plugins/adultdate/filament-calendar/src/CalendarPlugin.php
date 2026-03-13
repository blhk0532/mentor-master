<?php

declare(strict_types=1);

namespace Adultdate\FilamentBooking;

use Filament\Contracts\Plugin;
use Filament\Panel;

class CalendarPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'guava-calendar';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
