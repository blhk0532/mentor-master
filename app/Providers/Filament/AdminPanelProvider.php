<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\FilamentInfoWidget;
use App\Filament\Widgets\WorldClockWidget;
use App\Filament\Widgets\AccountInfoStackWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Enums\ThemeMode;
use Filament\Support\Enums\Width;
use App\Filament\Pages\Terminal;
use App\Filament\Pages\Terminals;
use MWGuerra\WebTerminal\WebTerminalPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->maxContentWidth(Width::Full)
            ->spaUrlExceptions(['tel:*', 'mailto:*'])
            ->sidebarCollapsibleOnDesktop(true)
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->favicon(fn () => asset('favicon.svg'))
            ->brandLogoHeight(fn () => request()->is('admin/login', 'admin/password-reset/*') ? '68px' : '44px')
            ->brandName('Noridic Digital')
            ->defaultThemeMode(ThemeMode::Dark)
            ->revealablePasswords(true)
            ->passwordReset()
            ->emailChangeVerification()
            ->spaUrlExceptions(['tel:*', 'mailto:*'])
            ->colors([
                'primary' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                WorldClockWidget::class,
                AccountInfoStackWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->pages([
                Terminal::class,
                Terminals::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                WebTerminalPlugin::make()
            ]);
        //    ->plugins([
        //        FilamentWirechatPlugin::make()
        //            ->onlyPages([])
        //            ->excludeResources([
        //                \AdultDate\FilamentWirechat\Filament\Resources\Conversations\ConversationResource::class,
        //                \AdultDate\FilamentWirechat\Filament\Resources\Messages\MessageResource::class,
        //            ]),
        //    ]);
    }//
}
