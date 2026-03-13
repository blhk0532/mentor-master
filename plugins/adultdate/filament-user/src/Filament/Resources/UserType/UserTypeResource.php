<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Filament\Resources\UserType;

use Adultdate\FilamentUser\Filament\Resources\UserType\Pages\CreateUserType;
use Adultdate\FilamentUser\Filament\Resources\UserType\Pages\EditUserType;
use Adultdate\FilamentUser\Filament\Resources\UserType\Pages\ListUserTypes;
use Adultdate\FilamentUser\Filament\Resources\UserType\Schemas\UserTypeForm;
use Adultdate\FilamentUser\Filament\Resources\UserType\Tables\UserTypesTable;
use Adultdate\FilamentUser\Models\UserType as UserTypeModel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserTypeResource extends Resource
{
    protected static ?string $model = UserTypeModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Schema $schema): Schema
    {
        return UserTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserTypes::route('/'),
            'create' => CreateUserType::route('/create'),
            'edit' => EditUserType::route('/{record}/edit'),
        ];
    }
}
