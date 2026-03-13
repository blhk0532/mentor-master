<?php

declare(strict_types=1);

namespace Adultdate\Schedule\Filament\Resources\Meetings\Pages;

use Adultdate\Schedule\Filament\Resources\Meetings\MeetingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMeeting extends CreateRecord
{
    protected static string $resource = MeetingResource::class;
}
