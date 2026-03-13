<?php

declare(strict_types=1);

namespace Adultdate\FilamentShop;

use Adultdate\FilamentShop\Commands\FilamentShopCommand;
use Adultdate\FilamentShop\Testing\TestsFilamentShop;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentShopServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-shop';

    public static string $viewNamespace = 'filament-shop';

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
                    ->askToStarRepoOnGitHub('adultdate/filament-shop');
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
        //                $file->getRealPath() => base_path("stubs/filament-shop/{$file->getFilename()}"),
        //            ], 'filament-shop-stubs');
        //        }
        //    }

        // Migration Publishing
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'adultdate-filament-shop-migrations');

        // Testing
        Testable::mixin(new TestsFilamentShop);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'adultdate/filament-shop';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {

        $distPath = __DIR__.'../../dist';

        if (is_dir($distPath)) {
            return [
                // AlpineComponent::make('filament-shop', __DIR__ . '/../resources/dist/components/filament-shop.js'),
                Css::make('filament-shop-styles', $distPath.'/resources/dist/filament-shop.css'),
                Js::make('filament-shop-scripts', $distPath.'/resources/dist/filament-shop.js'),
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
            FilamentShopCommand::class,
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
            'create_addressable_table',
            'create_addresses_table',
            'create_comments_table',
            'create_exports_table',
            'create_failed_import_rows_table',
            'create_imports_table',
            'create_media_table',
            'create_notifications_table',
            'create_payments_table',
            'create_settings_table',
            'create_shop_brands_table',
            'create_shop_categories_table',
            'create_shop_category_product_table',
            'create_shop_customers_table',
            'create_shop_order_items_table',
            'create_shop_orders_table',
            'create_shop_products_table',
            'create_tag_tables',
        ];
    }
}
