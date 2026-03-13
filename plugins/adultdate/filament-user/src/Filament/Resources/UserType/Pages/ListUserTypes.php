<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Filament\Resources\UserType\Pages;

use Adultdate\FilamentUser\Filament\Resources\UserType\UserTypeResource;
use Filament\Resources\Pages\ListRecords;

class ListUserTypes extends ListRecords
{
    protected static string $resource = UserTypeResource::class;
}
