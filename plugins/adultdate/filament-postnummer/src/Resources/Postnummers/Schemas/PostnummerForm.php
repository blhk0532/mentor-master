<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Resources\Postnummers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PostnummerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('post_nummer')
                    ->required(),
                TextInput::make('post_ort')
                    ->required(),
                TextInput::make('post_lan')
                    ->required(),
                TextInput::make('status')
                    ->default('idle'),
                TextInput::make('merinfo_personer_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('merinfo_foretag_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('merinfo_personer_phone_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('merinfo_personer_house_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('hitta_personer_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('hitta_foretag_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('ratsit_personer_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('ratsit_foretag_total')
                    ->numeric()
                    ->default(0),
                TextInput::make('created_at')
                    ->default(null),

                Toggle::make('is_active')
                    ->required(),
                Toggle::make('merinfo_personer_queue')
                    ->required(),
                Toggle::make('merinfo_personer_count')
                    ->required(),

            ]);
    }
}
