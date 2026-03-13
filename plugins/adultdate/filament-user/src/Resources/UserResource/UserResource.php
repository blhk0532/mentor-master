<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources;

use Adultdate\FilamentUser\Resources\UserResource\Pages\CreateUser;
use Adultdate\FilamentUser\Resources\UserResource\Pages\EditUser;
use Adultdate\FilamentUser\Resources\UserResource\Pages\ListUsers;
use Adultdate\FilamentUser\Resources\UserResource\Schemas\UserForm;
use Adultdate\FilamentUser\Resources\UserResource\Tables\UsersTable;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Forms\Form $form): Forms\Form
    {
        return UserForm::configure($form);
    }

    public static function table(Tables\Table $table): Tables\Table
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
