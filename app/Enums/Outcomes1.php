<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Outcomes1: string implements HasColor, HasIcon, HasLabel
{
    case DMC = '( DMC )';
    case Felnummer = 'Fel Telefonnummer';
    case Klickad = 'Klickad';
    case EjIntresserad = 'Ej Intresserad';

    public function getLabel(): string
    {
        return match ($this) {
            self::DMC => '( DMC )',
            self::Felnummer => 'Fel Nummer',
            self::Klickad => 'Klickad',
            self::EjIntresserad => 'Ej Intresse',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DMC => 'danger',
            self::Klickad => 'warning',
            self::EjIntresserad => 'warning',
            self::Felnummer => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::DMC => 'heroicon-m-hand-raised',
            self::Klickad => 'heroicon-m-x-circle',
            self::EjIntresserad => 'heroicon-m-hand-raised',
            self::Felnummer => 'heroicon-m-x-circle',
        };
    }

    public function getGroup(): string
    {
        return match ($this) {

        };
    }
}
