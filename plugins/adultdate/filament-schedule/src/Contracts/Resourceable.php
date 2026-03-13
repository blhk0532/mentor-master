<?php

declare(strict_types=1);

namespace Adultdate\Schedule\Contracts;

use Adultdate\Schedule\ValueObjects\CalendarResource;

interface Resourceable
{
    public function toCalendarResource(): CalendarResource;
}
