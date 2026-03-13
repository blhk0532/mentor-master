<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer\Pages;

use BackedEnum;
use Filament\Pages\Page;

class PhoneDialerPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Phone Dialer';

    protected static ?string $title = 'Phone Dialer';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament-dialer::pages.phone-dialer';

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
        return '5xl';
    }
}
