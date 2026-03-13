<?php

namespace App\Filament\Resources\SwedenPostnummers\Tables;

use App\Jobs\RunHittaDataScriptJob;
use App\Jobs\RunMerinfoDataScriptJob;
use App\Jobs\RunRatsitDataScriptJob;
use App\Models\HittaData;
use App\Models\MerinfoData;
use App\Models\RatsitData;
use App\Models\SwedenPostnummer;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class SwedenPostnummersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('csv_id')
                    ->hidden()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('post_nummer')
                    ->label('Postnr')
                    ->searchable(),
                TextColumn::make('post_ort')
                    ->searchable(),
                TextColumn::make('kommun')
                    ->label('Kommun')
                    ->searchable(),
                TextColumn::make('lan')
                    ->label('Län')
                    ->searchable(),
                TextColumn::make('country')
                    ->hidden()
                    ->label('Land')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('longitude')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('personer')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('foretag')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('personer_saved')
                    ->label('Saved')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('personer_ratsit_saved')
                    ->label('Ratsit')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('personer_hitta_saved')
                    ->label('Hitta')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('personer_merinfo_saved')
                    ->label('Merinfo')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                ToggleColumn::make('personer_merinfo_queue')
                    ->label('Queue')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('has_personer')
                    ->label('Has Personer')
                    ->default(true)
                    ->query(fn (Builder $query) => $query->where('personer', '>', 0)),
                SelectFilter::make('kommun')
                    ->label('Kommun')
                    ->searchable()
                    ->multiple()
                    ->options(fn (): array => SwedenPostnummer::query()
                        ->whereNotNull('kommun')
                        ->where('kommun', '<>', '')
                        ->orderBy('kommun')
                        ->pluck('kommun', 'kommun')
                        ->all()),
                SelectFilter::make('post_ort')
                    ->label('Postort')
                    ->searchable()
                    ->multiple()
                    ->options(fn (): array => SwedenPostnummer::query()
                        ->whereNotNull('post_ort')
                        ->where('post_ort', '<>', '')
                        ->orderBy('post_ort')
                        ->pluck('post_ort', 'post_ort')
                        ->all()),
                SelectFilter::make('lan')
                    ->label('Län')
                    ->searchable()
                    ->multiple()
                    ->options(fn (): array => SwedenPostnummer::query()
                        ->whereNotNull('lan')
                        ->where('lan', '<>', '')
                        ->orderBy('lan')
                        ->pluck('lan', 'lan')
                        ->all()),
            ])
            ->recordActions([
                Action::make('run')
                    ->label('Run')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Queue data jobs')
                    ->modalDescription(fn (SwedenPostnummer $record): string => "This will queue hitta_data.mjs, merinfo_data.mjs, and ratsit_data.mjs jobs for postnummer {$record->post_nummer}.")
                    ->modalSubmitActionLabel('Queue Jobs')
                    ->action(function (SwedenPostnummer $record): void {
                        $postNummer = $record->post_nummer;
                        $normalizedPostNummer = str_replace(' ', '', $postNummer);

                        $batch = Bus::batch([
                            new RunHittaDataScriptJob($normalizedPostNummer),
                            new RunMerinfoDataScriptJob($normalizedPostNummer),
                            new RunRatsitDataScriptJob($postNummer),
                        ])
                            ->name("SwedenPostnummer {$postNummer} data scripts")
                            ->onConnection(config('queue.default'))
                            ->onQueue('sweden-postnummer-data')
                            ->allowFailures()
                            ->dispatch();

                        Notification::make()
                            ->success()
                            ->title('Batch queued')
                            ->body("Queued batch {$batch->id} for {$postNummer}.")
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('setAllQueueFlags')
                        ->label('Set All Queue = 1')
                        ->icon('heroicon-o-queue-list')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Set Queue Columns')
                        ->modalDescription('This will set personer_hitta_queue, personer_merinfo_queue, and personer_ratsit_queue to 1 for all selected records.')
                        ->modalSubmitActionLabel('Set Queue = 1')
                        ->action(function (Collection $records): void {
                            $updated = 0;

                            foreach ($records as $record) {
                                SwedenPostnummer::query()
                                    ->whereKey($record->getKey())
                                    ->update([
                                        'personer_hitta_queue' => 1,
                                        'personer_merinfo_queue' => 1,
                                        'personer_ratsit_queue' => 1,
                                    ]);

                                $updated++;
                            }

                            Notification::make()
                                ->success()
                                ->title('Queue Columns Updated')
                                ->body("Set all queue columns to 1 for {$updated} record(s).")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('checkDbCounts')
                        ->label('Check DB Counts')
                        ->icon('heroicon-o-calculator')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Check Database Counts')
                        ->modalDescription('This will count matching rows in hitta_data, merinfo_data, and ratsit_data and update personer_hitta_saved, personer_merinfo_saved, and personer_ratsit_saved for selected records.')
                        ->modalSubmitActionLabel('Update Counts')
                        ->action(function (Collection $records): void {
                            $updated = 0;

                            foreach ($records as $record) {
                                $postNummer = (string) $record->post_nummer;
                                $normalizedPostNummer = $record->csv_id;

                                $hittaCount = HittaData::query()
                                    ->where('postnummer', $postNummer)
                                    ->count();

                                $merinfoCount = MerinfoData::query()
                                    ->where('postnummer', $normalizedPostNummer)
                                    ->count();

                                $ratsitCount = RatsitData::query()
                                    ->where('postnummer', $postNummer)
                                    ->count();

                                SwedenPostnummer::query()
                                    ->whereKey($record->getKey())
                                    ->update([
                                        'personer_hitta_saved' => $hittaCount,
                                        'personer_merinfo_saved' => $merinfoCount,
                                        'personer_ratsit_saved' => $ratsitCount,
                                    ]);

                                $updated++;
                            }

                            Notification::make()
                                ->success()
                                ->title('DB Counts Updated')
                                ->body("Updated saved counts for {$updated} record(s).")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('runAllData')
                        ->label('Run All Data Scripts')
                        ->icon('heroicon-o-play')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Run All Data Scripts')
                        ->modalDescription('This will queue data collection jobs (Hitta, Merinfo, Ratsit) for all selected records.')
                        ->modalSubmitActionLabel('Run All Scripts')
                        ->action(function (Collection $records): void {
                            $batchCount = 0;
                            $totalJobs = 0;

                            foreach ($records as $record) {
                                $postNummer = (string) $record->post_nummer;
                                $normalizedPostNummer = str_replace(' ', '', $postNummer);

                                $batch = Bus::batch([
                                    new RunHittaDataScriptJob($normalizedPostNummer),
                                    new RunMerinfoDataScriptJob($normalizedPostNummer),
                                    new RunRatsitDataScriptJob($postNummer),
                                ])
                                    ->name("SwedenPostnummer {$postNummer} data scripts")
                                    ->onConnection(config('queue.default'))
                                    ->onQueue('sweden-postnummer-data')
                                    ->allowFailures()
                                    ->dispatch();

                                $batchCount++;
                                $totalJobs += 3;
                            }

                            Notification::make()
                                ->success()
                                ->title('Batches Queued')
                                ->body("Queued {$batchCount} batch(es) with {$totalJobs} total job(s) for data collection.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    //        DeleteBulkAction::make(),
                ]),
            ]);
    }
}
