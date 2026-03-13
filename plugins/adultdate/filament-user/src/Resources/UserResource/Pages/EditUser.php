<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources\UserResource\Pages;

use Adultdate\FilamentUser\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
