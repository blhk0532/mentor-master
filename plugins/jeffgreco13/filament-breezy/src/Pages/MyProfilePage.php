<?php

declare(strict_types=1);

namespace Jeffgreco13\FilamentBreezy\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MyProfilePage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected string $view = 'filament-breezy::filament.pages.my-profile';

    public static function getSlug(?Panel $panel = null): string
    {
        return filament('filament-breezy')->slug();
    }

    public static function getNavigationLabel(): string
    {
        return 'Profile';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return filament('filament-breezy')->shouldRegisterNavigation('myProfile');
    }

    public static function getNavigationGroup(): ?string
    {
        return filament('filament-breezy')->getNavigationGroup('myProfile');
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        return ''.Str::ucfirst($user->role) ?? 'Guest';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament-breezy::default.profile.my_profile');
    }

    public function getHeading(): string|Htmlable
    {
        return __('filament-breezy::default.profile.my_profile');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('filament-breezy::default.profile.subheading') ?? null;
    }

    public function getRegisteredMyProfileComponents(): array
    {
        return filament('filament-breezy')->getRegisteredMyProfileComponents();
    }
}
