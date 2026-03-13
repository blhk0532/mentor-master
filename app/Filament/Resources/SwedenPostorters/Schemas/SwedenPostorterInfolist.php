<?php

namespace App\Filament\Resources\SwedenPostorters\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SwedenPostorterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('post_ort'),
                TextEntry::make('kommun')
                    ->placeholder('-'),
                TextEntry::make('lan')
                    ->placeholder('-'),
                TextEntry::make('latitude')
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->placeholder('-'),
                TextEntry::make('personer')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('foretag')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
