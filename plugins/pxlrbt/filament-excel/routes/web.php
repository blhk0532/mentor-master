<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('filament-excel/{path}', function (string $path) {
    $filename = mb_substr($path, 37);
    $path = Storage::disk('filament-excel')->path($path);

    return
        response()
            ->download($path, $filename)
            ->deleteFileAfterSend();
})
    ->middleware(['web', 'signed'])
    ->where('path', '.*')
    ->name('filament-excel-download');
