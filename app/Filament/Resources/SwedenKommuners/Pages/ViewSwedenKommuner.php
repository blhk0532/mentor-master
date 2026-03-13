<?php

namespace App\Filament\Resources\SwedenKommuners\Pages;

use App\Filament\Resources\SwedenKommuners\SwedenKommunerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSwedenKommuner extends ViewRecord
{
    protected static string $resource = SwedenKommunerResource::class;

    protected static ?string $title = 'View Kommun';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
