<?php

declare(strict_types=1);

namespace Adultdate\FilamentAuth;

use Adultdate\FilamentAuth\Filament\Pages\AuthLogin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Caresome\FilamentAuthDesigner\View\AuthDesignerRenderHook;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class FilamentAuthPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-auth';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->databaseNotifications()
            ->databaseTransactions()
            ->databaseNotificationsPolling('60s')
            ->plugin(
                AuthDesignerPlugin::make()
                    ->login(
                        fn (AuthPageConfig $config) => $config
                            ->media(asset('assets/background.jpg'))
                            ->mediaPosition(MediaPosition::Cover)
                            ->blur(1)
                            ->themeToggle()
                            ->blur(1)
                            ->themeToggle()
                            ->usingPage(AuthLogin::class)
                            ->renderHook(AuthDesignerRenderHook::CardBefore, fn () => view('filament-auth::auth-logo'))
                    )
            )
            ->plugin(
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
                        shouldRegisterNavigation: true, // Adds a main navigation item for the My Profile page (default = false)
                        navigationGroup: 'Users & Roles', // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'profile', // Sets the slug for the profile page (default = 'my-profile')
                    )
            )
            ->plugin(
                FilamentShieldPlugin::make()
                    ->navigationLabel('Roles')                  // string|Closure|null
                    ->navigationIcon('heroicon-o-shield-check')         // string|Closure|null
                    ->activeNavigationIcon('heroicon-s-shield-check')   // string|Closure|null
                    ->navigationGroup('Users & Roles')                  // string|Closure|null
                    ->navigationSort(10)                        // int|Closure|null
                    ->navigationBadge('Roles')                      // string|Closure|null
                    ->navigationBadgeColor('success')           // string|array|Closure|null
                //        ->shouldRegisterNavigation(fasle)
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
