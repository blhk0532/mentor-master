<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources\UserTypeResource\Pages;

use Adultdate\FilamentUser\Resources\UserTypeResource;
use Filament\Resources\Pages\ListRecords;

class ListUserTypes extends ListRecords
{
    protected static string $resource = UserTypeResource::class;
}
