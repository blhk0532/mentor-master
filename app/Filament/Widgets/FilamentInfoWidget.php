<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class FilamentInfoWidget extends Widget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.filament-info-widget';

    public function getColumnSpan(): int|array
    {
        return [
            'default' => 'full',
            'lg' => '1/2',
        ];
    }
}
