<?php

namespace App\Filament\Resources\SwedenKommuners\Tables;

use App\Actions\ImportSwedenKommunerCountsFromRatsit;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Throwable;

class SwedenKommunersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kommun')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('personer')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('foretag')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('latitude')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('longitude')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('importRatsitCounts')
                        ->label('Import Ratsit Counts')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Import Ratsit counts')
                        ->modalDescription('This updates only the selected sweden_kommuner personer and foretag values from current ratsit_kommuner counts. No rows are deleted or recreated.')
                        ->action(function (Collection $records, ImportSwedenKommunerCountsFromRatsit $importAction): void {
                            try {
                                $stats = $importAction->handle($records->modelKeys());

                                Notification::make()
                                    ->success()
                                    ->title('Ratsit counts imported')
                                    ->body("Processed {$stats['processed']} selected rows, updated {$stats['updated']}, unchanged {$stats['unchanged']}, unmatched {$stats['unmatched']}.")
                                    ->send();
                            } catch (Throwable $throwable) {
                                Notification::make()
                                    ->danger()
                                    ->title('Import failed')
                                    ->body($throwable->getMessage())
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
