<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Resources\UserTypeResource\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Card::make()->schema([
                TextInput::make('slug')->required()->unique(\Adultdate\FilamentUser\Models\UserType::class, 'slug', ignoreRecord: true),
                TextInput::make('label')->required()->maxLength(255),
            ]),
        ]);
    }
}
