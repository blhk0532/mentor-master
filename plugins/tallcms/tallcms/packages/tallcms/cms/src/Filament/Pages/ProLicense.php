<?php

namespace Tallcms\Pro\Filament\Pages;

use App\Services\PluginLicenseService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * @deprecated License management has moved to core (Settings > Plugin Licenses)
 * This page is hidden but kept for backwards compatibility
 */
class ProLicense extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Pro License';

    protected static ?string $title = 'TallCMS Pro License';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 100;

    protected string $view = 'tallcms-pro::filament.pages.pro-license';

    /**
     * Hide from navigation - license management is now in core
     */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?string $license_key = '';

    public array $status = [];

    public function mount(): void
    {
        $this->refreshStatus();
    }

    protected function refreshStatus(): void
    {
        $this->status = app(PluginLicenseService::class)->getStatus('tallcms/pro');
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('license_key')
                ->label('License Key')
                ->placeholder('XXXX-XXXX-XXXX-XXXX')
                ->required()
                ->helperText('Enter your TallCMS Pro license key from your purchase email'),
        ];
    }

    public function activateLicense(): void
    {
        $data = $this->form->getState();

        if (empty($data['license_key'])) {
            Notification::make()
                ->title('License key is required')
                ->danger()
                ->send();

            return;
        }

        $result = app(PluginLicenseService::class)->activate('tallcms/pro', $data['license_key']);

        if ($result['valid']) {
            Notification::make()
                ->title('License Activated')
                ->body('TallCMS Pro has been successfully activated!')
                ->success()
                ->send();

            $this->license_key = '';
            $this->refreshStatus();
        } else {
            Notification::make()
                ->title('Activation Failed')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }

    public function deactivateLicense(): void
    {
        $result = app(PluginLicenseService::class)->deactivate('tallcms/pro');

        if ($result['success']) {
            Notification::make()
                ->title('License Deactivated')
                ->body('Your license has been deactivated from this site.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Deactivation Notice')
                ->body($result['message'])
                ->warning()
                ->send();
        }

        $this->refreshStatus();
    }

    public function refreshLicenseStatus(): void
    {
        $licenseService = app(PluginLicenseService::class);
        $licenseService->clearCache('tallcms/pro');
        $licenseService->isValid('tallcms/pro');

        $this->refreshStatus();

        Notification::make()
            ->title('Status Refreshed')
            ->body('License status has been refreshed from the server.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Status')
                ->icon('heroicon-o-arrow-path')
                ->action('refreshLicenseStatus')
                ->visible(fn () => $this->status['has_license'] ?? false),
        ];
    }
}
