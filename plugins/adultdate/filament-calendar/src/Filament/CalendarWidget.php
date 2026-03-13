<?php

declare(strict_types=1);

namespace Adultdate\FilamentBooking\Filament;

use Adultdate\FilamentBooking\Concerns\InteractsWithCalendar;
use Adultdate\FilamentBooking\Contracts\HasCalendar;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Widgets\Widget;

abstract class CalendarWidget extends Widget implements HasActions, HasCalendar, HasSchemas
{
    use InteractsWithCalendar;

    protected string $view = 'guava-calendar::widgets.calendar-widget';

    protected int|string|array $columnSpan = 'full';
}
