<?php

declare(strict_types=1);

namespace Hexters\HexaLite;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Hexters\HexaLite\Resources\Roles\RoleResource;
use Hexters\HexaLite\Traits\GateTrait;

class HexaLite implements Plugin
{
    use GateTrait;

    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-hexa-lite';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            RoleResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        $this->registerGates($panel);
        $this->registerGateList($panel);

        $panel->navigationItems([
            NavigationItem::make(__('Role & Permissions'))
                ->visible(fn () => hexa()->can('role.index'))
                ->url(RoleResource::getUrl())
                ->isActiveWhen(fn () => request()->fullUrlIs(RoleResource::getUrl().'*'))
                ->icon(Heroicon::OutlinedLockClosed)
                ->group(__('Settings')),
        ]);
    }
}
