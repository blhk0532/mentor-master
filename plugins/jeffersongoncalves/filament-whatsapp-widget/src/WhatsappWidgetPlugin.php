<?php

declare(strict_types=1);

namespace JeffersonGoncalves\Filament\WhatsappWidget;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\Filament\WhatsappWidget\Resources\WhatsappAgentResource;
use JeffersonGoncalves\Filament\WhatsappWidget\Support\Utils;

class WhatsappWidgetPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'filament-whatsapp-widget';
    }

    public function register(Panel $panel): void
    {
        if (! Utils::isResourcePublished($panel)) {
            $panel->resources([WhatsappAgentResource::class]);
        }
    }

    public function boot(Panel $panel): void {}
}
