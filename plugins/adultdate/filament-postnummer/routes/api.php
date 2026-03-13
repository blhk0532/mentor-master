<?php

declare(strict_types=1);

use Adultdate\FilamentPostnummer\Http\Controllers\Api\PostnummerApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/postnummer')->group(function () {
    Route::get('/', [PostnummerApiController::class, 'index']);
    Route::get('/{postNummer}', [PostnummerApiController::class, 'show']);
    Route::put('/{postNummer}', [PostnummerApiController::class, 'update']);
    Route::post('/bulk-update', [PostnummerApiController::class, 'bulkUpdate']);
});
