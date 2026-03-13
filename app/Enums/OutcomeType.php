<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OutcomeType: string implements HasColor, HasIcon, HasLabel
{
    case Block = 'Block';
    case Delete = 'Delete';
    case No = 'No';
    case Retry = 'Retry';
    case Later = 'Later';
    case Return = 'Return';
    case Maybe = 'Maybe';
    case Yes = 'Yes';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Block => 'danger',
            self::Delete => 'danger',
            self::No => 'warning',
            self::Retry => 'gray',
            self::Later => 'gray',
            self::Return => 'gray',
            self::Maybe => 'success',
            self::Yes => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Block => 'heroicon-o-no-symbol',
            self::Delete => 'heroicon-o-trash',
            self::No => 'heroicon-o-x-mark',
            self::Retry => 'heroicon-o-arrow-path',
            self::Later => 'heroicon-o-clock',
            self::Return => 'heroicon-o-arrow-left',
            self::Maybe => 'heroicon-o-question-mark-circle',
            self::Yes => 'heroicon-o-check-circle',
        };
    }
}
