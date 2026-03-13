<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Filament\Resources\User\Pages;

use Adultdate\FilamentUser\Filament\Resources\User\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
