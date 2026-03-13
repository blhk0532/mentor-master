<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources\UserResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->searchable(),
            TextColumn::make('phone'),
            TextColumn::make('role')->sortable(),
            TextColumn::make('type.label')->label('Type'),
            TextColumn::make('team'),
        ])->filters([
            // add filters here
        ])->actions([
            \Filament\Tables\Actions\EditAction::make(),
        ])->bulkActions([
            \Filament\Tables\Actions\DeleteBulkAction::make(),
        ]);
    }
}
