<?php

declare(strict_types=1);

namespace Adultdate\FilamentAuth;

use Adultdate\FilamentAuth\Commands\FilamentAuthCommand;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAuthServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-auth';

    public static string $viewNamespace = 'filament-auth';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(self::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('adultdate/filament-auth');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(self::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        //    // Handle Stubs
        //    if (app()->runningInConsole()) {
        //        foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
        //            $this->publishes([
        //                $file->getRealPath() => base_path("stubs/filament-auth/{$file->getFilename()}"),
        //            ], 'filament-auth-stubs');
        //        }
        //    }

        // Migration Publishing
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'adultdate-filament-auth-migrations');

    }

    protected function getAssetPackageName(): ?string
    {
        return 'adultdate/filament-auth';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {

        $distPath = __DIR__.'../../dist';

        if (is_dir($distPath)) {
            return [
                // AlpineComponent::make('filament-auth', __DIR__ . '/../resources/dist/components/filament-auth.js'),
                Css::make('filament-auth-styles', $distPath.'/resources/dist/filament-auth.css'),
                Js::make('filament-auth-scripts', $distPath.'/resources/dist/filament-auth.js'),
            ];
        }

        return [];

    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentAuthCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'add_user_extra_columns',
            'create_permission_tables',
            'create_user_settings_table',
            'create_user_types_table',
            'create_user_stats_table',
            'create_teams_table',
        ];
    }
}
