<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources;

use Adultdate\FilamentUser\Models\UserType;
use Adultdate\FilamentUser\Resources\UserTypeResource\Pages\CreateUserType;
use Adultdate\FilamentUser\Resources\UserTypeResource\Pages\EditUserType;
use Adultdate\FilamentUser\Resources\UserTypeResource\Pages\ListUserTypes;
use Adultdate\FilamentUser\Resources\UserTypeResource\Schemas\UserTypeForm;
use Adultdate\FilamentUser\Resources\UserTypeResource\Tables\UserTypesTable;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class UserTypeResource extends Resource
{
    protected static ?string $model = UserType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Forms\Form $form): Forms\Form
    {
        return UserTypeForm::configure($form);
    }

    public static function table(Tables\Table $table): Tables\Table
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
