<?php

declare(strict_types=1);

namespace Shreejan\DashArrange;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * DashArrange Plugin.
 *
 * Filament plugin for customizable dashboard widgets.
 */
class DashArrangePlugin implements Plugin
{
    /**
     * Create a new plugin instance.
     */
    public static function make(): static
    {
        return app(self::class);
    }

    /**
     * Get the plugin instance from Filament.
     */
    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    /**
     * Get the plugin ID.
     */
    public function getId(): string
    {
        return 'dash-arrange';
    }

    /**
     * Register the plugin.
     */
    public function register(Panel $panel): void
    {
        //
    }

    /**
     * Boot the plugin.
     */
    public function boot(Panel $panel): void
    {
        //
    }
}
