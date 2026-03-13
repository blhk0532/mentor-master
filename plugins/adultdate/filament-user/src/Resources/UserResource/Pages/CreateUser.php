<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources\UserResource\Pages;

use Adultdate\FilamentUser\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
