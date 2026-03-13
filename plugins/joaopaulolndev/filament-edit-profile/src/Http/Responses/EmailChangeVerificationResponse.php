<?php

declare(strict_types=1);

namespace Joaopaulolndev\FilamentEditProfile\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\EmailChangeVerificationResponse as Responsable;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Livewire\Features\SupportRedirects\Redirector;

class EmailChangeVerificationResponse implements Responsable
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $panel = Filament::getCurrentOrDefaultPanel();
        $tenant = filament()->getTenant();

        if (! $tenant) {
            $user = Filament::auth()->user();

            if ($user && method_exists($user, 'getDefaultTenant')) {
                $tenant = $user->getDefaultTenant($panel);
            }

            if (! $tenant && $user && method_exists($user, 'getTenants')) {
                $tenant = collect($user->getTenants($panel))->first();
            }
        }

        $targetUrl = $tenant
            ? EditProfilePage::getUrl(tenant: $tenant)
            : ($panel?->getUrl() ?? url('/'));

        return redirect()->intended($targetUrl);
    }
}
