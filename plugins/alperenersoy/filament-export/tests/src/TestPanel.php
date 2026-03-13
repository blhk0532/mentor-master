<?php

namespace AlperenErsoy\FilamentExport\Tests;

use AlperenErsoy\FilamentExport\Tests\Filament\Resources\PostResource;
use AlperenErsoy\FilamentExport\Tests\Filament\Resources\UserResource;
use Filament\Panel;
use Filament\PanelProvider;

class TestPanel extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('test-panel')
            ->default()
            ->resources([
                UserResource::class,
                PostResource::class,
            ]);
    }
}
