<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Filament\Resources\User\Pages;

use Adultdate\FilamentUser\Filament\Resources\User\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
