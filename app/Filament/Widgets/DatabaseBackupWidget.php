<?php

declare(strict_types=1);

namespace App\Filament\Queue\Widgets;

use Exception;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class DatabaseBackupWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected string $view = 'filament.widgets.database-backup-widget';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isDiscovered = false;

    public function backupAction(): Action
    {
        return Action::make('backup')
            ->label('Backup Database')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Backup Database')
            ->modalDescription('This will create a backup of the current database. The backup file will be downloaded automatically.')
            ->modalSubmitActionLabel('Create Backup')
            ->action(function () {
                try {
                    $database = config('database.default');
                    $connection = config("database.connections.{$database}");
                    $timestamp = now()->format('Y-m-d_H-i-s');
                    $filename = "backup_{$connection['database']}_{$timestamp}.sql";

                    // Ensure backup directory exists
                    $backupPath = storage_path('app/backups');
                    if (! file_exists($backupPath)) {
                        mkdir($backupPath, 0755, true);
                    }

                    $filepath = "{$backupPath}/{$filename}";

                    // Check if exec function is available
                    if (! function_exists('exec')) {
                        throw new Exception('exec() function is disabled on this server');
                    }

                    // Create mysqldump command
                    $command = sprintf(
                        'mysqldump -u%s -p%s %s > %s',
                        escapeshellarg($connection['username']),
                        escapeshellarg($connection['password']),
                        escapeshellarg($connection['database']),
                        escapeshellarg($filepath)
                    );

                    // Execute backup with error suppression
                    @exec($command, $output, $returnVar);

                    if ($returnVar !== 0) {
                        throw new Exception('Database backup failed');
                    }

                    // Check if file was created
                    if (! file_exists($filepath) || filesize($filepath) === 0) {
                        throw new Exception('Backup file was not created or is empty');
                    }

                    Notification::make()
                        ->success()
                        ->title('Database Backup Created')
                        ->body("Backup saved to: storage/app/backups/{$filename}")
                        ->persistent()
                        ->send();

                    // Return download response
                    return response()->download($filepath)->deleteFileAfterSend(false);
                } catch (Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Backup Failed')
                        ->body($e->getMessage())
                        ->persistent()
                        ->send();
                }
            });
    }

    public function importAction(): Action
    {
        return Action::make('import')
            ->label('Import Database')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading('Import Database')
            ->modalDescription('⚠️ WARNING: This will REPLACE ALL current database data with data from the backup file. This action cannot be undone. A pre-import backup will be created automatically.')
            ->modalSubmitActionLabel('Import Backup')
            ->schema([
                FileUpload::make('file')
                    ->label('SQL Backup File')
                    ->acceptedFileTypes(['application/sql', 'application/x-sql', '.sql', 'text/plain'])
                    ->required()
                    ->maxSize(512000) // 500MB max
                    ->helperText('Upload a .sql backup file to restore the database.'),
            ])
            ->action(function (array $data) {
                try {
                    if (empty($data['file'])) {
                        throw new Exception('No file uploaded');
                    }

                    $uploadedFile = $data['file'];
                    $database = config('database.default');
                    $connection = config("database.connections.{$database}");

                    // Ensure the file exists
                    $filePath = storage_path('app/public/'.$uploadedFile);
                    if (! file_exists($filePath)) {
                        // Try without public folder
                        $filePath = storage_path('app/'.$uploadedFile);
                        if (! file_exists($filePath)) {
                            throw new Exception('Uploaded file not found');
                        }
                    }

                    // Create a backup before importing
                    $timestamp = now()->format('Y-m-d_H-i-s');
                    $backupFilename = "pre_import_backup_{$connection['database']}_{$timestamp}.sql";
                    $backupPath = storage_path('app/backups');

                    if (! file_exists($backupPath)) {
                        mkdir($backupPath, 0755, true);
                    }

                    $backupFilepath = "{$backupPath}/{$backupFilename}";

                    // Create backup before import
                    $backupCommand = sprintf(
                        'mysqldump -u%s -p%s %s > %s',
                        escapeshellarg($connection['username']),
                        escapeshellarg($connection['password']),
                        escapeshellarg($connection['database']),
                        escapeshellarg($backupFilepath)
                    );

                    exec($backupCommand, $backupOutput, $backupReturnVar);

                    if ($backupReturnVar !== 0) {
                        throw new Exception('Failed to create pre-import backup');
                    }

                    // Import the database
                    $importCommand = sprintf(
                        'mysql -u%s -p%s %s < %s',
                        escapeshellarg($connection['username']),
                        escapeshellarg($connection['password']),
                        escapeshellarg($connection['database']),
                        escapeshellarg($filePath)
                    );

                    exec($importCommand, $output, $returnVar);

                    if ($returnVar !== 0) {
                        throw new Exception('Database import failed. Your data has been preserved. Pre-import backup saved to: '.$backupFilename);
                    }

                    Notification::make()
                        ->success()
                        ->title('Database Import Successful')
                        ->body("Database has been restored from backup. A pre-import backup was saved to: storage/app/backups/{$backupFilename}")
                        ->persistent()
                        ->send();

                    // Redirect to refresh the page
                    return redirect()->to(request()->header('Referer', '/'));
                } catch (Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body($e->getMessage())
                        ->persistent()
                        ->send();
                }
            });
    }
}
