<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Resources\Postnummers\Pages;

use Adultdate\FilamentPostnummer\Resources\Postnummers\PostnummerResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPostnummer extends ViewRecord
{
    protected static string $resource = PostnummerResource::class;
}
