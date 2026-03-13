<?php

use App\Providers\Adultdate\ChatsPanelProvider;
use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\FolioServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\VoltServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;

return [
    ChatsPanelProvider::class,
    BroadcastServiceProvider::class,
    AppServiceProvider::class,
    AdminPanelProvider::class,
    FolioServiceProvider::class,
    VoltServiceProvider::class,
    FortifyServiceProvider::class,
];
