<?php

declare(strict_types=1);

namespace Adultdate\Schedule\Filament\Resources\Schedules\Pages;

use Adultdate\Schedule\Filament\Resources\Schedules\ScheduleResource;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;
}
