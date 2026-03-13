<?php

declare(strict_types=1);

namespace App\Filament\Admin\Support;

use App\Filament\Admin\Widgets\AccountInfoStackWidget;
use App\Filament\Admin\Widgets\FilamentInfoWidget;
use Filament\Widgets\Widget;

trait StackWidget
{
    /**
     * @return array<class-string<Widget>>
     */
    protected function getStackedWidgets(): array
    {
        return [
            AccountInfoStackWidget::class,
            FilamentInfoWidget::class,

        ];
    }

    protected function getViewData(): array
    {
        return [
            'stackedWidgets' => $this->getStackedWidgets(),
        ];
    }
}
