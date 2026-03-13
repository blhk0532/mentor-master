<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Admin\Support\StackWidget;
use Filament\Widgets\Widget;

class AccountInfoStackWidget extends Widget
{
    use StackWidget;

    protected static bool $isLazy = false;

    protected static ?int $sort = -3;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.stack';

    protected function getStackedWidgets(): array
    {
        return [
            AccountWidget::class,
            FilamentInfoWidget::class,

        ];
    }
}
