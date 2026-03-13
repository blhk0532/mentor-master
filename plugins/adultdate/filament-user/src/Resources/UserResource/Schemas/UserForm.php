<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources\UserResource\Schemas;

use Adultdate\FilamentUser\Models\UserType;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Card::make()->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->maxLength(255),
                TextInput::make('phone')->tel()->maxLength(50),
                TextInput::make('role')->maxLength(100),
                Select::make('type_id')
                    ->label('Type')
                    ->options(fn () => UserType::pluck('label', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('team')->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->maxLength(255),
            ])->columns(2),
        ]);
    }
}
