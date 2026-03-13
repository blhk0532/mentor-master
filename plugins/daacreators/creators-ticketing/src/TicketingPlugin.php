<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing;

use daacreators\CreatorsTicketing\Filament\Resources\AutomationRules\AutomationRuleResource;
use daacreators\CreatorsTicketing\Filament\Resources\Departments\DepartmentResource;
use daacreators\CreatorsTicketing\Filament\Resources\Forms\FormResource;
use daacreators\CreatorsTicketing\Filament\Resources\Tickets\TicketResource;
use daacreators\CreatorsTicketing\Filament\Resources\TicketStatuses\TicketStatusResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class TicketingPlugin implements Plugin
{
    public static function make(): static
    {
        return new self;
    }

    public function getId(): string
    {
        return 'creators-ticketing';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            FormResource::class,
            DepartmentResource::class,
            TicketResource::class,
            TicketStatusResource::class,
            AutomationRuleResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
