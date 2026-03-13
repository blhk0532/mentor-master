<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Resources\Postnummers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PostnummerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('post_nummer'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('post_ort'),
                TextEntry::make('post_lan'),

                TextEntry::make('merinfo_personer_total')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('merinfo_foretag_total')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('merinfio_personer_phone_saved')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('hitta_personer_house_saved')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('hitta_personer_total')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('hitta_foretag_total')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('ratsit_personer_total')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('ratsit_foretag_total')
                    ->numeric()
                    ->placeholder('-'),

                TextEntry::make('merinfo_personer_saved')
                    ->numeric()
                    ->placeholder('-'),

                TextEntry::make('hitta_personer_saved')
                    ->numeric()
                    ->placeholder('-'),

                TextEntry::make('ratsit_personer_saved')
                    ->numeric()
                    ->placeholder('-'),

                TextEntry::make('merinfo_personer_phone_saved')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('hitta_personer_phone_saved')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('ratsit_personer_phone_saved')
                    ->numeric()
                    ->placeholder('-'),

                TextEntry::make('merinfo_personer_house_saved')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('hitta_personer_house_saved')
                    ->numeric()
                    ->placeholder('-'),

            ]);
    }
}
