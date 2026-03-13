<?php

namespace Tallcms\Pro\Filament\Widgets;

use App\Services\PluginLicenseService;
use Filament\Widgets\Widget;

class LicenseStatusWidget extends Widget
{
    protected string $view = 'tallcms-pro::filament.widgets.license-status';

    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    public function getStatus(): array
    {
        return app(PluginLicenseService::class)->getStatus('tallcms/pro');
    }

    public static function canView(): bool
    {
        // Only show if Pro plugin has a license (active or expired)
        return app(PluginLicenseService::class)->hasEverBeenLicensed('tallcms/pro');
    }
}
