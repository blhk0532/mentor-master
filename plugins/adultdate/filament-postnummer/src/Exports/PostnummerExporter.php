<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Exports;

use Adultdate\FilamentPostnummer\Models\Postnummer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PostnummerExporter extends Exporter
{
    protected static ?string $model = Postnummer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('post_nummer')
                ->label('Postnummer'),
            ExportColumn::make('post_ort')
                ->label('Ort'),
            ExportColumn::make('post_lan')
                ->label('Län'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('is_active')
                ->label('Aktiv')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nej'),

            // Hitta data
            ExportColumn::make('hitta_personer_total')
                ->label('Hitta Personer Total'),
            ExportColumn::make('hitta_foretag_total')
                ->label('Hitta Företag Total'),
            ExportColumn::make('hitta_personer_saved')
                ->label('Hitta Personer Sparade'),
            ExportColumn::make('hitta_foretag_saved')
                ->label('Hitta Företag Sparade'),
            ExportColumn::make('hitta_personer_phone_saved')
                ->label('Hitta Personer Telefon Sparade'),
            ExportColumn::make('hitta_personer_house_saved')
                ->label('Hitta Personer Hus Sparade'),

            // Ratsit data
            ExportColumn::make('ratsit_personer_total')
                ->label('Ratsit Personer Total'),
            ExportColumn::make('ratsit_foretag_total')
                ->label('Ratsit Företag Total'),
            ExportColumn::make('ratsit_personer_saved')
                ->label('Ratsit Personer Sparade'),
            ExportColumn::make('ratsit_foretag_saved')
                ->label('Ratsit Företag Sparade'),
            ExportColumn::make('ratsit_personer_phone_saved')
                ->label('Ratsit Personer Telefon Sparade'),
            ExportColumn::make('ratsit_personer_house_saved')
                ->label('Ratsit Personer Hus Sparade'),

            // Merinfo data
            ExportColumn::make('merinfo_personer_total')
                ->label('Merinfo Personer Total'),
            ExportColumn::make('merinfo_foretag_total')
                ->label('Merinfo Företag Total'),
            ExportColumn::make('merinfo_personer_phone_total')
                ->label('Merinfo Personer Telefon Total'),
            ExportColumn::make('merinfo_foretag_phone_total')
                ->label('Merinfo Företag Telefon Total'),
            ExportColumn::make('merinfo_personer_saved')
                ->label('Merinfo Personer Sparade'),
            ExportColumn::make('merinfo_foretag_saved')
                ->label('Merinfo Företag Sparade'),
            ExportColumn::make('merinfo_personer_phone_saved')
                ->label('Merinfo Personer Telefon Sparade'),
            ExportColumn::make('merinfo_personer_house_saved')
                ->label('Merinfo Personer Hus Sparade'),

            // Queue flags
            ExportColumn::make('hitta_personer_queue')
                ->label('Hitta Personer Kö')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nej'),
            ExportColumn::make('hitta_foretag_queue')
                ->label('Hitta Företag Kö')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nej'),
            ExportColumn::make('ratsit_personer_queue')
                ->label('Ratsit Personer Kö')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nej'),
            ExportColumn::make('ratsit_foretag_queue')
                ->label('Ratsit Företag Kö')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nej'),
            ExportColumn::make('merinfo_personer_queue')
                ->label('Merinfo Personer Kö')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nej'),
            ExportColumn::make('merinfo_foretag_queue')
                ->label('Merinfo Företag Kö')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nej'),

            ExportColumn::make('created_at')
                ->label('Skapad'),
            ExportColumn::make('updated_at')
                ->label('Uppdaterad'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return "Postnummer exporten är klar. {$export->total_rows} rader exporterades.";
    }

    public static function getFailedNotificationBody(Export $export): string
    {
        return "Postnummer exporten misslyckades. {$export->total_rows} rader kunde inte exporteras.";
    }
}
