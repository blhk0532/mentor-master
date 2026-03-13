<?php

declare(strict_types=1);

namespace Jeffgreco13\FilamentBreezy\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class MyProfileComponent extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public static $sort = 0;

    public static function canView(): bool
    {
        return true;
    }

    public static function getSort(): int
    {
        return self::$sort;
    }

    public static function setSort(int $sort): void
    {
        self::$sort = $sort;
    }

    public function getName()
    {
        return str(self::class)->afterLast('\\')->snake();
    }

    public function render()
    {
        return view($this->view);
    }
}
