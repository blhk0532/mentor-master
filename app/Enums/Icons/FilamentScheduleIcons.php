<?php

declare(strict_types=1);

namespace App\Enums\Icons;

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Enums\IconSize;

enum FilamentScheduleIcons: string implements ScalableIcon
{
    case History = 'history';

    public function getIconForSize(IconSize $size): string
    {
        return match ($size) {
            default => "schedule-$this->value",
        };
    }
}
