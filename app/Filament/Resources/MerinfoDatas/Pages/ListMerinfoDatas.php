<?php

declare(strict_types=1);

namespace App\Filament\Resources\MerinfoDatas\Pages;

use App\Filament\Resources\MerinfoDatas\MerinfoDataResource;
use App\Filament\Widgets\MerinfoDataStatsWidget;
use App\Jobs\BackupMerinfoData;
use App\Jobs\ImportMerinfoData;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ListMerinfoDatas extends ListRecords
{
    protected static string $resource = MerinfoDataResource::class;

    // Hide the default header title for this resource (removes the <h1 class="fi-header-heading">)
    protected ?string $heading = '';

    protected function getHeaderWidgets(): array
    {
        return [
            MerinfoDataStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Data')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->action(function (array $data): void {
                    $this->handleImport($data['file'], $data['file_type']);
                })
                ->schema([
                    Select::make('file_type')
                        ->label('File Type')
                        ->options([
                            'csv' => 'CSV',
                            'xlsx' => 'Excel (XLSX/XLS)',
                            'sqlite' => 'SQLite Database',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $set('file', null); // Clear file when type changes
                        }),

                    FileUpload::make('file')
                        ->label('File')
                        ->required()
                        ->directory('imports')
                        ->visibility('private')
                        ->acceptedFileTypes(function (Get $get) {
                            return match ($get('file_type')) {
                                'csv' => ['text/csv', 'text/plain'],
                                'xlsx' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                                'sqlite' => ['application/x-sqlite3', 'application/octet-stream'],
                                default => [],
                            };
                        })
                        ->maxSize(function (Get $get) {
                            return match ($get('file_type')) {
                                'sqlite' => 51200, // 50MB for SQLite
                                default => 10240, // 10MB for others
                            };
                        })
                        ->helperText(function (Get $get) {
                            return match ($get('file_type')) {
                                'csv' => 'Upload a CSV file with headers matching database columns.',
                                'xlsx' => 'Upload an Excel file (.xlsx or .xls) with data in the first sheet.',
                                'sqlite' => 'Upload a SQLite database file containing a merinfo_data table.',
                                default => '',
                            };
                        }),
                ])
                ->modalHeading('Import Merinfo Data')
                ->modalDescription('Choose a file type and upload your data file to import into the Merinfo database.')
                ->modalSubmitActionLabel('Start Import'),

            Action::make('backupDatabase')
                ->label('Backup DB')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Backup Merinfo Data Table')
                ->modalDescription('This will queue a background job to create a SQLite backup of the merinfo_data table in the database/export folder. You will receive a notification when the backup is complete.')
                ->modalSubmitActionLabel('Queue Backup Job')
                ->action(function (): void {
                    try {
                        // Dispatch the backup job
                        BackupMerinfoData::dispatch();

                        Notification::make()
                            ->title('Backup Job Queued')
                            ->body('The backup job has been queued and will run in the background. You will be notified when it completes.')
                            ->success()
                            ->send();

                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Failed to Queue Backup')
                            ->body('Error queuing backup job: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            CreateAction::make(),
        ];
    }

    protected function handleImport(array $files, string $fileType): void
    {
        $filePath = $files[0]; // FileUpload returns array
        /** @var \App\Models\User|null $authUser */
        $authUser = auth()->user();
        $userId = $authUser?->id;

        try {
            // Dispatch the appropriate import job
            match ($fileType) {
                'csv' => ImportMerinfoData::dispatch($filePath, 'csv', $userId),
                'xlsx' => ImportMerinfoData::dispatch($filePath, 'xlsx', $userId),
                'sqlite' => ImportMerinfoData::dispatch($filePath, 'sqlite', $userId),
            };

            Notification::make()
                ->title('Import Job Queued')
                ->body("The {$fileType} import job has been queued and will run in the background. You will receive a notification when it completes.")
                ->success()
                ->send();

        } catch (Exception $e) {
            Notification::make()
                ->title('Failed to Queue Import')
                ->body('Error queuing import job: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }
}
