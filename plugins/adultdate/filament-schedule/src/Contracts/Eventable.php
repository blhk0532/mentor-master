<?php

declare(strict_types=1);

namespace Adultdate\Schedule\Contracts;

use Adultdate\Schedule\ValueObjects\CalendarEvent;

interface Eventable
{
    public function toCalendarEvent(): CalendarEvent;
}
