<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Filament\Resources\User;

use Adultdate\FilamentUser\Filament\Resources\User\Pages\CreateUser;
use Adultdate\FilamentUser\Filament\Resources\User\Pages\EditUser;
use Adultdate\FilamentUser\Filament\Resources\User\Pages\ListUsers;
use Adultdate\FilamentUser\Filament\Resources\User\Schemas\UserForm;
use Adultdate\FilamentUser\Filament\Resources\User\Tables\UsersTable;
use Adultdate\FilamentUser\Models\User as UserModel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = UserModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
