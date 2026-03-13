<?php

declare(strict_types=1);

namespace Adultdate\FilamentBooking\Filament\Actions;

use Adultdate\FilamentBooking\Concerns\CalendarAction;
use Adultdate\FilamentBooking\Contracts\HasCalendar;
use Filament\Schemas\Schema;

class CreateAction extends \Filament\Actions\CreateAction
{
    use CalendarAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->schema(
                fn (Schema $schema, CreateAction $action, HasCalendar $livewire) => $livewire
                    ->getFormSchemaForModel($schema, $action->getModel())
            )
            ->after(fn (HasCalendar $livewire) => $livewire->refreshRecords())
            ->cancelParentActions();
    }
}
