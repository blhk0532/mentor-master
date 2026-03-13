<?php

declare(strict_types=1);

namespace Adultdate\FilamentShop\Enums;

enum BookingStatus: string
{
    case Booked = 'booked';
    case Changed = 'changed';
    case Processing = 'processing';
    case Cancelled = 'cancelled';
    case Updated = 'updated';
    case Complete = 'complete';

    public static function toOptions(): array
    {
        return array_map(fn (self $s) => $s->getLabel(), self::cases());
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Booked => 'Booked',
            self::Changed => 'Changed',
            self::Processing => 'Processing',
            self::Cancelled => 'Cancelled',
            self::Updated => 'Updated',
            self::Complete => 'Complete',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Booked => 'primary',
            self::Changed => 'warning',
            self::Processing => 'secondary',
            self::Cancelled => 'danger',
            self::Updated => 'info',
            self::Complete => 'success',
        };
    }

    public function getCalendarColor(): string
    {
        return match ($this) {
            self::Booked => '#3b82f6',     // Blue
            self::Changed => '#f59e0b',    // Amber
            self::Processing => '#94a3b8',  // Slate
            self::Cancelled => '#ef4444',   // Red
            self::Updated => '#0ea5e9',     // Sky
            self::Complete => '#22c55e',    // Green
        };
    }
}
