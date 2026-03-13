<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Outcomes4: string implements HasColor, HasIcon, HasLabel
{
    case NyligenGjort = 'Nyligen Gjort';
    case Offert = 'Offert';
    case Aterkommer = 'Återkommer';
    case RingTillbaka = 'Ring Tillbaka';

    //   case Yes = 'Bokad';

    public function getLabel(): string
    {
        return match ($this) {
            self::NyligenGjort => 'Redan Gjort',
            self::Offert => 'Skicka Offert',
            default => $this->value,
        };
    }

    public function getColor(): string
    {
        return match ($this) {

            self::NyligenGjort => '',
            self::RingTillbaka => '',
            self::Aterkommer => '',
            //    self::Yes => 'success',
            self::Offert => '',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {

            self::NyligenGjort => 'heroicon-m-clock',
            self::Aterkommer => 'heroicon-m-phone',
            self::RingTillbaka => 'heroicon-m-phone-arrow-up-right',
            self::Offert => 'heroicon-m-check-circle',
            //   self::Yes => 'heroicon-m-check-circle',
        };
    }

    public function getGroup(): string
    {
        return match ($this) {

        };
    }
}
