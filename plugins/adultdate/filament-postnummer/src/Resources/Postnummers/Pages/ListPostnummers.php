<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Resources\Postnummers\Pages;

use Adultdate\FilamentPostnummer\Resources\Postnummers\PostnummerResource;
use Filament\Resources\Pages\ListRecords;

class ListPostnummers extends ListRecords
{
    protected static string $resource = PostnummerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions will be added here
        ];
    }
}
