<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Outcomes: string implements HasColor, HasIcon, HasLabel
{
    case DMC = '( DMC )';
    case Klickad = 'Klickad';
    case EjIntresserad = 'Ej Intresserad';
    case Felnummer = 'Fel Telefonnummer';

    case EjFramkopplad = 'Ej Framkopplad';
    case Upptagen = 'Upptagen';
    case IngetSvar = 'Inget Svar';
    case Voicemail = 'Telefonsvar';

    case RingIgen3 = 'Ring Igen 3';
    case RingIgen6 = 'Ring Igen 6';
    case RingIgen12 = 'Ring Igen 12';
    case RingIgen24 = 'Ring Igen 24';

    case NyligenGjort = 'Nyligen Gjort';
    case Aterkommer = 'Återkommer';
    case RingTillbaka = 'Ring Tillbaka';
    case Offert = 'Offert';
    case Kontakt = 'Kontakt';
    case Yes = 'Bokning';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DMC => 'danger',
            self::Klickad => 'danger',
            self::EjIntresserad => 'danger',
            self::Felnummer => 'danger',
            self::EjFramkopplad => 'primary',
            self::Upptagen => 'primary',
            self::IngetSvar => 'primary',
            self::Voicemail => 'primary',
            self::RingIgen3 => 'warning',
            self::RingIgen6 => 'warning',
            self::RingIgen12 => 'warning',
            self::RingIgen24 => 'warning',
            self::NyligenGjort => 'gray',
            self::Aterkommer => 'primary',
            self::RingTillbaka => 'primary',
            self::Yes => 'success',
            self::Offert => 'success',
            self::Kontakt => 'primary',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::DMC => 'heroicon-m-hand-raised',
            self::Klickad => 'heroicon-m-x-circle',
            self::EjIntresserad => 'heroicon-m-hand-raised',
            self::Felnummer => 'heroicon-m-x-circle',
            self::EjFramkopplad => 'heroicon-m-phone',
            self::Upptagen => 'heroicon-m-phone-x-mark',
            self::IngetSvar => 'heroicon-m-phone-arrow-down-left',
            self::Voicemail => 'heroicon-m-chat-bubble-left-right',
            self::RingIgen3 => 'heroicon-m-clock',
            self::RingIgen6 => 'heroicon-m-clock',
            self::RingIgen12 => 'heroicon-m-clock',
            self::RingIgen24 => 'heroicon-m-clock',
            self::NyligenGjort => 'heroicon-m-clock',
            self::Aterkommer => 'heroicon-m-phone',
            self::RingTillbaka => 'heroicon-m-phone-arrow-up-right',
            self::Yes => 'heroicon-m-check-circle',
            self::Offert => 'heroicon-m-check-circle',
            self::Kontakt => 'heroicon-m-user',
        };
    }
}
