<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources\UserTypeResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('slug')->sortable()->searchable(),
            TextColumn::make('label')->sortable()->searchable(),
        ])->filters([])->actions([
            \Filament\Tables\Actions\EditAction::make(),
        ])->bulkActions([
            \Filament\Tables\Actions\DeleteBulkAction::make(),
        ]);
    }
}
