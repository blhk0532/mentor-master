<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserActiveStatus: string implements HasColor, HasIcon, HasLabel
{
    case Online = 'online';
    case Away = 'away';
    case Busy = 'busy';
    case Offline = 'offline';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Online => 'Online',
            self::Away => 'Away',
            self::Busy => 'Busy',
            self::Offline => 'Offline',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Online => 'success',
            self::Away => 'warning',
            self::Busy => 'danger',
            self::Offline => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Online => 'heroicon-m-check-circle',
            self::Away => 'heroicon-m-clock',
            self::Busy => 'heroicon-m-minus-circle',
            self::Offline => 'heroicon-m-x-circle',
        };
    }
}
