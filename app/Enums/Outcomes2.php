<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Outcomes2: string implements HasColor, HasIcon, HasLabel
{
    case EjFramkopplad = 'Ej Framkopplad';
    case Upptagen = 'Upptagen';
    case Voicemail = 'Telefonsvar';
    case IngetSvar = 'Inget Svar';

    public function getLabel(): string
    {
        return match ($this) {
            self::EjFramkopplad => 'Ej Kopplad',
            self::Upptagen => 'Upptagen',
            self::Voicemail => 'Telefonsvar',
            self::IngetSvar => 'Inget Svar',
        };
    }

    public function getColor(): string
    {
        return match ($this) {

            self::EjFramkopplad => 'primary',
            self::Upptagen => 'primary',
            self::IngetSvar => 'primary',
            self::Voicemail => 'primary',

        };
    }

    public function getIcon(): string
    {
        return match ($this) {

            self::EjFramkopplad => 'heroicon-m-phone',
            self::Upptagen => 'heroicon-m-phone-x-mark',
            self::IngetSvar => 'heroicon-m-phone-arrow-down-left',
            self::Voicemail => 'heroicon-m-chat-bubble-left-right',

        };
    }

    public function getGroup(): string
    {
        return match ($this) {

        };
    }
}
