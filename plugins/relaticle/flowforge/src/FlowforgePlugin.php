<?php

declare(strict_types=1);

namespace Relaticle\Flowforge;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Livewire\Livewire;

// use Relaticle\Flowforge\Livewire\KanbanBoard;

class FlowforgePlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'flowforge';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        // Livewire::component('relaticle.flowforge.livewire.kanban-board', KanbanBoard::class);
    }
}
