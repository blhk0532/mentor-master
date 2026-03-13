<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer\Pages;

use BackedEnum;
use Filament\Pages\Page;

class AutoDialerPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Autodialer';

    protected static ?string $title = 'Autodialer';

    protected static ?int $navigationSort = 20;

    protected static bool $isDiscovered = false;

    protected string $view = 'filament-dialer::pages.autodialer';

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public function getMaxWidth(): string
    {
        return 'full';
    }
}
