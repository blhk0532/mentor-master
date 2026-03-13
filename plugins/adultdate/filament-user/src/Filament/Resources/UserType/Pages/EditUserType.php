<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Filament\Resources\UserType\Pages;

use Adultdate\FilamentUser\Filament\Resources\UserType\UserTypeResource;
use Filament\Resources\Pages\EditRecord;

class EditUserType extends EditRecord
{
    protected static string $resource = UserTypeResource::class;
}
