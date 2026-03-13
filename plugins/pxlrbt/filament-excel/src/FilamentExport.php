<?php

declare(strict_types=1);

namespace pxlrbt\FilamentExcel;

use Closure;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FilamentExport
{
    public static ?Closure $createExportUrlUsing = null;

    public static function createExportUrlUsing(Closure $closure): void
    {
        self::$createExportUrlUsing = $closure;
    }

    public static function getNotificationCacheKey($userId): string
    {
        return 'filament-excel:exports:'.$userId;
    }

    public function sendNotification(): void
    {
        $exports = cache()->pull(self::getNotificationCacheKey(Filament::auth()->id()));

        if (! filled($exports)) {
            return;
        }

        foreach ($exports as $export) {
            if (! Storage::disk('filament-excel')->exists($export['filename'])) {
                continue;
            }

            $url = $this->createUrl($export);

            if (Filament::getCurrentPanel()->hasDatabaseNotifications()) {
                $this->sendDatabaseNotification($export, $url);
            } else {
                $this->sendPersistentNotification($export, $url);
            }
        }
    }

    private function sendDatabaseNotification(array $export, string $url): void
    {
        $previousLocale = app()->getLocale();

        if (isset($export['locale'])) {
            app()->setLocale($export['locale']);
        }

        Notification::make(data_get($export, 'id'))
            ->title(__('filament-excel::notifications.download_ready.title'))
            ->body(__('filament-excel::notifications.download_ready.body'))
            ->success()
            ->icon('heroicon-o-document')
            ->actions([
                Action::make('download')
                    ->label(__('filament-excel::notifications.download_ready.download'))
                    ->url($url, shouldOpenInNewTab: true)
                    ->button()
                    ->close(),
            ])
            ->sendToDatabase(Filament::auth()->user());

        app()->setLocale($previousLocale);
    }

    private function sendPersistentNotification(array $export, string $url): void
    {
        Notification::make(data_get($export, 'id'))
            ->title(__('filament-excel::notifications.download_ready.title'))
            ->body(__('filament-excel::notifications.download_ready.body'))
            ->success()
            ->icon('heroicon-o-document')
            ->actions([
                Action::make('download')
                    ->label(__('filament-excel::notifications.download_ready.download'))
                    ->url($url, shouldOpenInNewTab: true)
                    ->button()
                    ->close(),
            ])
            ->persistent()
            ->send();
    }

    private function createUrl(array $export): string
    {
        if (self::$createExportUrlUsing !== null) {
            return (self::$createExportUrlUsing)($export);
        }

        return URL::temporarySignedRoute(
            'filament-excel-download',
            now()->addHours(24),
            ['path' => $export['filename']]
        );
    }
}
