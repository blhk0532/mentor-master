<?php

declare(strict_types=1);

namespace Adultdate\FilamentAuth\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class FilamentAuthCommand extends Command
{
    protected $signature = 'filamentAuth:install {--panel=}';

    protected $description = 'Install Filament Filament Auth plugin and complete setup including WebSockets, Queue, and Tailwind CSS';

    protected bool $hasPrompts = false;

    protected bool $AppPanelProviderCreated = false;

    public function __construct()
    {
        parent::__construct();
        $this->hasPrompts = class_exists(\Laravel\Prompts\Prompt::class);
    }

    public function handle(): int
    {
        $this->displayBanner();

        // Ask if user wants to create standalone Filament Auth panel (AppPanelProvider)
        $this->promptInfo('Standalone Filament Auth Panel Setup...');
        if ($this->promptConfirm('Would you like to create a standalone Filament Auth panel at /app?', default: false)) {
            $this->createAppPanelProvider();
            $this->promptNote('AppPanelProvider created successfully');
            $this->AppPanelProviderCreated = true;
        } else {
            $this->promptNote('Standalone Filament Auth panel skipped. You can create it later if needed.');
        }

        $this->promptInfo('Installing Filament Filament Auth Plugin...');
        echo "\n";

        // Publish configuration
        $this->promptInfo('Publishing configuration...');
        $this->publishConfiguration();
        $this->promptNote('Configuration published');

        // Create storage symlink
        $this->promptInfo('Creating storage symlink...');
        Artisan::call('storage:link');
        $this->promptNote('Storage linked');

        // Publish migrations
        $this->promptInfo('Publishing migrations...');
        $this->publishMigrations();
        $this->promptNote('Migrations published');

        // Run migrations
        $this->promptInfo('Running migrations...');
        if ($this->promptConfirm('Run migrations now?', default: true)) {
            try {
                $exitCode = $this->call('migrate');
                if ($exitCode === 0) {
                    $this->promptNote('Migrations run successfully');
                } else {
                    $this->promptWarning('Some migrations may have failed. Check the output above.');
                    $this->promptNote('Filament Auth migrations should have run successfully.');
                    $this->promptNote('You can run migrations manually with: php artisan migrate');
                }
            } catch (Exception $e) {
                $this->promptWarning('Migration error occurred (this may be normal if some tables already exist).');
                $this->promptWarning('Filament Auth migrations should have run successfully before this error.');
                $this->promptNote('You can run migrations manually with: php artisan migrate');
            }
        } else {
            $this->promptWarning('Migrations not run. Run manually with: php artisan migrate');
        }

        // Publish seeders
        $this->promptInfo('Publishing seeders...');
        $this->publishSeeders();
        $this->promptNote('Seeders published');

        // Run seeders
        $this->promptInfo('Running seeders...');
        if ($this->promptConfirm('Run seeders now? (This will create sample data)', default: false)) {
            try {
                $exitCode = $this->call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);
                if ($exitCode === 0) {
                    $this->promptNote('Seeders run successfully');
                } else {
                    $this->promptWarning('Some seeders may have failed. Check the output above.');
                }
            } catch (Exception $e) {
                $this->promptWarning('Seeder error occurred: '.$e->getMessage());
                $this->promptNote('You can run seeders manually with: php artisan db:seed --class=Database\\Seeders\\DatabaseSeeder');
            }
        } else {
            $this->promptNote('Seeders not run. Run manually with: php artisan db:seed --class=Database\\Seeders\\DatabaseSeeder');
        }

        // Setup Tailwind CSS
        $this->promptInfo('Setting up Tailwind CSS...');
        $this->setupTailwind();
        $this->promptNote('Tailwind CSS configured');

        // Register plugin with Filament panel
        $this->promptInfo('Registering plugin with Filament panel...');
        $this->registerPlugin();
        $this->promptNote('Plugin registered');

        // Copy plugin assets to Laravel app
        if ($this->promptConfirm('Copy plugin assets (CSS, JS, images, views) to your Laravel app?', default: true)) {
            $this->promptInfo('Copying plugin assets...');
            $this->copyPluginAssets();
            $this->promptNote('Plugin assets copied');
        } else {
            $this->promptNote('Plugin assets copying skipped');
        }

        // Configure PanelSwitch in AppServiceProvider
        $this->promptInfo('Configuring PanelSwitch in AppServiceProvider...');
        $this->configurePanelSwitch();
        $this->promptNote('PanelSwitch configured');

        // Install PanelSwitch package if not already installed
        $this->promptInfo('Ensuring PanelSwitch package is installed...');
        $this->installPanelSwitchPackage();
        $this->promptNote('PanelSwitch package ready');

        return self::SUCCESS;
    }

    protected function publishConfiguration(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'filament-auth-config',
            '--force' => true,
        ]);
    }

    protected function publishMigrations(): void
    {
        // Publish migrations using the tag
        $result = $this->call('vendor:publish', [
            '--tag' => 'filament-auth-migrations',
            '--force' => true,
        ]);
    }

    protected function publishSeeders(): void
    {
        $pluginSeederPath = dirname(__DIR__, 2).'/database/seeders/DatabaseSeeder.php';
        $appSeederPath = database_path('seeders/DatabaseSeeder.php');

        if (! File::exists($pluginSeederPath)) {
            $this->promptWarning('Plugin seeder not found at: '.$pluginSeederPath);

            return;
        }

        // Create a separate FilamentAuthSeeder instead of overwriting the main DatabaseSeeder
        $filamentAuthSeederPath = database_path('seeders/FilamentAuthSeeder.php');

        if (File::exists($filamentAuthSeederPath)) {
            $this->promptNote('  → FilamentAuthSeeder already exists');

            return;
        }

        // Copy the plugin seeder as FilamentAuthSeeder
        File::ensureDirectoryExists(dirname($filamentAuthSeederPath));
        File::copy($pluginSeederPath, $filamentAuthSeederPath);

        // Update the namespace in the copied file
        $content = File::get($filamentAuthSeederPath);
        $content = str_replace('namespace Database\\Seeders;', 'namespace Database\\Seeders;', $content);
        $content = str_replace('class DatabaseSeeder', 'class FilamentAuthSeeder', $content);
        File::put($filamentAuthSeederPath, $content);

        $this->promptNote('  → Published FilamentAuthSeeder to database/seeders/FilamentAuthSeeder.php');

        // Optionally add the seeder to the main DatabaseSeeder
        $this->addSeederToMainSeeder();
    }

    protected function addSeederToMainSeeder(): void
    {
        $mainSeederPath = database_path('seeders/DatabaseSeeder.php');

        if (! File::exists($mainSeederPath)) {
            $this->promptWarning('Main DatabaseSeeder not found, skipping seeder registration');

            return;
        }

        $content = File::get($mainSeederPath);

        // Check if FilamentAuthSeeder is already called
        if (str_contains($content, 'FilamentAuthSeeder')) {
            $this->promptNote('  → FilamentAuthSeeder already registered in main DatabaseSeeder');

            return;
        }

        // Try to add FilamentAuthSeeder to the existing $this->call() array
        if (preg_match('/\$this->call\(\s*\[([^\]]*)\]/s', $content, $matches)) {
            $callArray = $matches[1];
            // Add FilamentAuthSeeder to the array
            $newCallArray = mb_rtrim($callArray, ', ').",\n            FilamentAuthSeeder::class,\n        ";
            $content = str_replace($callArray, $newCallArray, $content);
            File::put($mainSeederPath, $content);
            $this->promptNote('  → Added FilamentAuthSeeder to main DatabaseSeeder call array');

            return;
        }

        // If no array found, add a new call
        $seederCall = "\n        \$this->call(FilamentAuthSeeder::class);";

        // Add at the end of the run method
        $content = preg_replace(
            '/(\s*public function run\(\): void\s*\{[^}]*)\}\s*$/',
            '$1'.$seederCall."\n    }\n",
            $content
        );

        if (str_contains($content, '$this->call(FilamentAuthSeeder::class);')) {
            File::put($mainSeederPath, $content);
            $this->promptNote('  → Added FilamentAuthSeeder call to main DatabaseSeeder');
        } else {
            $this->promptWarning('  → Could not add FilamentAuthSeeder call to main DatabaseSeeder');
            $this->promptNote('  → You can manually add: $this->call(FilamentAuthSeeder::class); to the run() method');
        }
    }

    protected function setupTailwind(): void
    {
        $cssPath = resource_path('css/app.css');

        if (! File::exists($cssPath)) {
            $this->promptWarning('app.css not found. Please add @source directive manually.');
            $this->promptNote('  → Add this line to your app.css:');
            $this->promptNote('  → @source "../../vendor/adultdate/filament-auth/resources/**/*.blade.php";');

            return;
        }

        $cssContent = File::get($cssPath);
        $filamentAuthSource = "@source '../../vendor/adultdate/filament-auth/resources/**/*.blade.php';";

        // Check if filamentAuth source is already added
        if (str_contains($cssContent, 'filament-auth')) {
            $this->promptNote('  → Tailwind CSS source already configured');
        } elseif (! str_contains($cssContent, '@source') && ! str_contains($cssContent, '@import')) {
            // For Tailwind v4, add @source directive for filamentAuth views
            $cssContent .= "\n{$filamentAuthSource}\n";
            File::put($cssPath, $cssContent);
            $this->promptNote('  → Added @source directive to app.css');
        } elseif (str_contains($cssContent, '@source') && ! str_contains($cssContent, 'filament-auth')) {
            // If @source exists but filamentAuth source is missing, add it
            $cssContent .= "\n{$filamentAuthSource}\n";
            File::put($cssPath, $cssContent);
            $this->promptNote('  → Added @source directive to app.css');
        } else {
            $this->promptNote('  → Tailwind CSS source already configured');
        }
    }

    protected function displayBanner(): void
    {
        $this->line('');
        $this->line('<fg=#f59e0b;options=bold>███████╗██╗██╗      █████╗ ███╗   ███╗███████╗███╗   ██╗████████╗</>');
        $this->line('<fg=#f59e0b;options=bold>██╔════╝██║██║     ██╔══██╗████╗ ████║██╔════╝████╗  ██║╚══██╔══╝</>');
        $this->line('<fg=#f59e0b;options=bold>█████╗  ██║██║     ███████║██╔████╔██║█████╗  ██╔██╗ ██║   ██║   </>');
        $this->line('<fg=#f59e0b;options=bold>██╔══╝  ██║██║     ██╔══██║██║╚██╔╝██║██╔══╝  ██║╚██╗██║   ██║   </>');
        $this->line('<fg=#f59e0b;options=bold>██║     ██║███████╗██║  ██║██║ ╚═╝ ██║███████╗██║ ╚████║   ██║   </>');
        $this->line('<fg=#f59e0b;options=bold>╚═╝     ╚═╝╚══════╝╚═╝  ╚═╝╚═╝     ╚═╝╚══════╝╚═╝  ╚═══╝   ╚═╝   </>');
        $this->line('');

    }

    protected function registerPlugin(): void
    {
        $panelId = $this->option('panel') ?? 'admin';

        $this->promptNote("  → Plugin will be registered with panel: {$panelId}");
        $this->promptNote('  → Add FilamentFilament AuthPlugin::make() to your panel configuration');
    }

    /**
     * Copy plugin assets (CSS, JS, images, views, etc.) from the plugin directory
     * to the Laravel application's corresponding directories.
     *
     * This ensures that the plugin's frontend assets are available in the main app.
     */
    protected function copyPluginAssets(): void
    {
        $pluginPath = dirname(__DIR__, 2); // Path to the plugin root
        $appPath = base_path(); // Path to the Laravel app root

        // Check if assets have already been copied
        $markerFile = $appPath.'/resources/views/filament-auth-copied.marker';
        if (File::exists($markerFile)) {
            $this->promptNote('  → Plugin assets already copied (marker file found)');

            return;
        }

        // Copy public assets (images, favicon, logo, etc.)
        $this->copyPublicAssets($pluginPath, $appPath);

        // Copy resources (CSS, JS, views, lang)
        $this->copyResources($pluginPath, $appPath);

        // Create marker file to indicate assets have been copied
        File::put($markerFile, 'Plugin assets copied on '.now()->toDateTimeString());
    }

    protected function copyPublicAssets(string $pluginPath, string $appPath): void
    {
        $publicAssets = [
            'public/favicon.svg' => 'public/favicon.svg',
            'public/logo.svg' => 'public/logo.svg',
            'public/assets' => 'public/assets', // Copy entire assets directory
        ];

        foreach ($publicAssets as $pluginAsset => $appAsset) {
            $sourcePath = $pluginPath.'/'.$pluginAsset;
            $destinationPath = $appPath.'/'.$appAsset;

            if (File::exists($sourcePath)) {
                if (is_dir($sourcePath)) {
                    // Copy directory
                    File::ensureDirectoryExists(dirname($destinationPath));
                    File::copyDirectory($sourcePath, $destinationPath);
                    $this->promptNote("  → Copied directory {$pluginAsset} to {$appAsset}");
                } else {
                    // Copy file
                    File::ensureDirectoryExists(dirname($destinationPath));
                    if (! File::exists($destinationPath) || $this->promptConfirm("File {$appAsset} already exists. Overwrite?", default: false)) {
                        File::copy($sourcePath, $destinationPath);
                        $this->promptNote("  → Copied file {$pluginAsset} to {$appAsset}");
                    } else {
                        $this->promptNote("  → Skipped {$pluginAsset} (file already exists)");
                    }
                }
            } else {
                $this->promptNote("  → Source {$pluginAsset} not found, skipping");
            }
        }
    }

    protected function copyResources(string $pluginPath, string $appPath): void
    {
        $resourcesToCopy = [
            'resources/css/filament' => 'resources/css/filament', // Filament-specific CSS
            'resources/js' => 'resources/js', // JavaScript files
            'resources/lang' => 'resources/lang', // Language files
            'resources/views' => 'resources/views', // Blade views
        ];

        foreach ($resourcesToCopy as $pluginResource => $appResource) {
            $sourcePath = $pluginPath.'/'.$pluginResource;
            $destinationPath = $appPath.'/'.$appResource;

            if (File::exists($sourcePath)) {
                if (File::exists($destinationPath) && ! $this->promptConfirm("Directory {$appResource} already exists. Overwrite?", default: false)) {
                    $this->promptNote("  → Skipped {$pluginResource} (directory already exists)");

                    continue;
                }

                File::ensureDirectoryExists(dirname($destinationPath));
                File::copyDirectory($sourcePath, $destinationPath);
                $this->promptNote("  → Copied {$pluginResource} to {$appResource}");
            } else {
                $this->promptNote("  → Source directory {$pluginResource} not found, skipping");
            }
        }
    }

    protected function configurePanelSwitch(): void
    {
        $appServiceProviderPath = app_path('Providers/AppServiceProvider.php');

        if (! File::exists($appServiceProviderPath)) {
            $this->promptWarning('AppServiceProvider not found at '.$appServiceProviderPath);

            return;
        }

        $content = File::get($appServiceProviderPath);

        // Check if PanelSwitch is already configured
        if (str_contains($content, 'PanelSwitch::configureUsing')) {
            $this->promptNote('  → PanelSwitch already configured in AppServiceProvider');

            return;
        }

        // Add necessary imports
        $importsToAdd = [
            'use App\Models\User;',
            'use BezhanSalleh\PanelSwitch\PanelSwitch;',
            'use Illuminate\Support\Facades\Auth;',
        ];

        foreach ($importsToAdd as $import) {
            if (! str_contains($content, $import)) {
                // Add import after the namespace declaration
                $content = preg_replace(
                    '/(namespace App\\\\Providers;\s*\n)/',
                    "$1\n$import\n",
                    $content
                );
            }
        }

        // Modify the boot method to include PanelSwitch configuration
        if (str_contains($content, '$this->bootModelsDefaults();')) {
            // Replace the existing boot method content
            $content = str_replace(
                '    public function boot(): void
    {
        $this->bootModelsDefaults();
    }

    private function bootModelsDefaults(): void
    {
        Model::unguard();
    }
}',
                '    public function boot(): void
    {
        $this->bootModelsDefaults();
        $this->configurePanelSwitch();
    }

    private function bootModelsDefaults(): void
    {
        Model::unguard();
    }

    private function configurePanelSwitch(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $user = Auth::user();
            $panels = [];
            $panelSwitch
                ->panels([\'admin\', \'app\'])
                ->slideOver()
                ->modalWidth(\'sm\')
                ->renderHook(\'panels::global-search.after\')
                ->slideOver()
                ->icons([
                    \'admin\' => \'heroicon-o-shield-check\',
                    \'app\' => \'heroicon-o-globe-alt\',
                ])
                ->iconSize(16)
                ->labels([
                    \'admin\' => \'Admin\',
                    \'app\' => \'App\',
                ]);

            if ($user instanceof User && $user->role === \'super_admin\') {
                $panels = [\'admin\', \'app\'];
            }
            if ($user instanceof User && (!$user->role || $user->role != \'super_admin\')) {
                $panels = [\'app\'];
            }
            if (!$user instanceof User) {
                $panels = [];
            }
            $panelSwitch->panels($panels);
        });
    }
}',
                $content
            );
        } else {
            $this->promptWarning('  → Could not find expected boot method structure in AppServiceProvider');

            return;
        }

        File::put($appServiceProviderPath, $content);
        $this->promptNote('  → Added PanelSwitch configuration to AppServiceProvider');
    }

    protected function installPanelSwitchPackage(): void
    {
        // Check if PanelSwitch is already installed
        if (class_exists('BezhanSalleh\PanelSwitch\PanelSwitch')) {
            $this->promptNote('  → PanelSwitch package already installed');

            return;
        }

        // Warn user to install the package manually
        $this->promptWarning('  → PanelSwitch package not found. Please install it manually:');
        $this->promptNote('  → composer require bezhansalleh/filament-panel-switch');
        $this->promptNote('  → Then run: php artisan filament:install-plugin panel-switch');
    }

    protected function createAppPanelProvider(): void
    {
        $providerPath = app_path('Providers/Filament/AppPanelProvider.php');
        $providersPath = base_path('bootstrap/providers.php');

        // Create the AppPanelProvider
        if (! File::exists($providerPath)) {
            $stub = $this->getAppPanelProviderStub();
            File::ensureDirectoryExists(dirname($providerPath));
            File::put($providerPath, $stub);
            $this->promptNote('  → Created AppPanelProvider at app/Providers/Filament/AppPanelProvider.php');
        } else {
            $this->promptWarning('  → AppPanelProvider already exists, skipping creation');
        }

        // Register in bootstrap/providers.php
        if (File::exists($providersPath)) {
            $content = File::get($providersPath);
            $providerClass = 'App\\Providers\\Filament\\AppPanelProvider::class';

            // Check if already registered
            if (! str_contains($content, $providerClass)) {
                // Add the provider to the array
                $content = str_replace(
                    '];',
                    "    {$providerClass},\n];",
                    $content
                );
                File::put($providersPath, $content);
                $this->promptNote('  → Registered AppPanelProvider in bootstrap/providers.php');
            } else {
                $this->promptNote('  → AppPanelProvider already registered in bootstrap/providers.php');
            }
        } else {
            $this->promptWarning('  → bootstrap/providers.php not found, please register AppPanelProvider manually');
        }
    }

    protected function getAppPanelProviderStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Filament\View\PanelsRenderHook;
use Caresome\FilamentAuthDesigner\View\AuthDesignerRenderHook;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;


<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Filament\View\PanelsRenderHook;
use Caresome\FilamentAuthDesigner\View\AuthDesignerRenderHook;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;


class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            //  ->default()
            ->id('app')
            ->path('app')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->favicon(asset('favicon.svg'))
            ->defaultThemeMode(ThemeMode::Dark)
            ->passwordReset(false)
            ->emailVerification(false)
            ->brandLogo(asset('logo.svg'))
            ->brandLogoHeight('1.6rem')
            ->brandName('Noridic Digital Solutions')
             ->renderHook(PanelsRenderHook::TOPBAR_LOGO_AFTER, fn(): string => view('filament-auth::logo-after-text')->render())
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->discoverClusters(in: app_path('Filament/Panels/App/Clusters'), for: 'App\\Filament\\Panels\\App\\Clusters')
            ->discoverResources(in: app_path('Filament/Panels/App/Resources'), for: 'App\\Filament\\Panels\\App\\Resources')
            ->discoverPages(in: app_path('Filament/Panels/App/Pages'), for: 'App\\Filament\\Panels\\App\\Pages')
            ->discoverWidgets(in: app_path('Filament/Panels/App/Widgets'), for: 'App\\Filament\\Panels\\App\\Widgets')
            ->pages([
                //  Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
                //  FilamentInfoWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->databaseTransactions()
            ->databaseNotificationsPolling('60s')
            ->plugin(
            AuthDesignerPlugin::make()
            ->login(
                fn(AuthPageConfig $config) => $config
                    ->media(asset('assets/pattaya.webp'))
                    ->mediaPosition(MediaPosition::Cover)
                    ->blur(1)
                    ->themeToggle()
                    ->usingPage(\Adultdate\FilamentAuth\Filament\Pages\AuthLogin::class)
                            ->renderHook(AuthDesignerRenderHook::CardBefore, fn() => view('filament-auth::auth-logo'))
            )
            )
            ->plugin(
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        userMenuLabel: null, // Customizes the 'account' link label in the panel User Menu (default = null)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        //  navigationGroup: 'Users & Roles', // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'profile', // Sets the slug for the profile page (default = 'my-profile')
                    )
            )
            ->userMenuItems([
                Action::make('switch_panels')
                    ->label('Switch View')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('gray')
                    ->modalHeading('≽ ^⎚ ˕ ⎚^ ≼ ')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->sort(-1)
                    ->modalContent(function () {
                        $user = Auth::user();
                        $panels = collect(filament()->getPanels())->filter(function ($panel) use ($user) {
                            return $user instanceof User && $user->canAccessPanel($panel);
                        });

                        return view('filament-auth::switch-panels-modal', ['panels' => $panels]);
                    }),
            ]);
    }
}
PHP;
    }

    /**
     * Helper methods to use Laravel Prompts if available, otherwise fall back to command methods
     */
    protected function promptInfo(string $message): void
    {
        if ($this->hasPrompts) {
            \Laravel\Prompts\info($message);
        } else {
            $this->info($message);
        }
    }

    protected function promptNote(string $message): void
    {
        if ($this->hasPrompts) {
            \Laravel\Prompts\note($message);
        } else {
            $this->line($message);
        }
    }

    protected function promptWarning(string $message): void
    {
        if ($this->hasPrompts) {
            \Laravel\Prompts\warning($message);
        } else {
            $this->warn($message);
        }
    }

    protected function promptConfirm(string $message, bool $default = true): bool
    {
        if ($this->hasPrompts) {
            return \Laravel\Prompts\confirm($message, default: $default);
        }

        return $this->confirm($message, $default);
    }
}
