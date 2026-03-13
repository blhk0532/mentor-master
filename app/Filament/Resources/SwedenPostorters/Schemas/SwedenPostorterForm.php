<?php

namespace App\Filament\Resources\SwedenPostorters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SwedenPostorterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('post_ort')
                    ->required(),
                TextInput::make('kommun'),
                TextInput::make('lan'),
                TextInput::make('latitude'),
                TextInput::make('longitude'),
                TextInput::make('personer')
                    ->numeric(),
                TextInput::make('foretag')
                    ->numeric(),
            ]);
    }
}
