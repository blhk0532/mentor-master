<?php

declare(strict_types=1);

namespace Buildix\Timex\Resources\EventResource\Pages;

use Auth;
use Buildix\Timex\Resources\EventResource;
use Buildix\Timex\Traits\TimexTrait;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Schema;

class ListEvents extends ListRecords
{
    use TimexTrait;

    protected static string $resource = EventResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if (in_array('participants', Schema::getColumnListing(self::getEventTableName()))) {
            return parent::getTableQuery()
                ->where('organizer', '=', Auth::id())
                ->orWhereJsonContains('participants', Auth::id());
        }

        return parent::getTableQuery();

    }
}
