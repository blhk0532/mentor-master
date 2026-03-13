<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Filament\Resources\User\Pages;

use Adultdate\FilamentUser\Filament\Resources\User\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
