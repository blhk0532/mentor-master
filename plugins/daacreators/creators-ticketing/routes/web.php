<?php

declare(strict_types=1);

use daacreators\CreatorsTicketing\Http\Controllers\TicketAttachmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/private/ticket-attachments/{ticketId}/{filename}',
        [TicketAttachmentController::class, 'show']
    )->name('creators-ticketing.attachment');
});
