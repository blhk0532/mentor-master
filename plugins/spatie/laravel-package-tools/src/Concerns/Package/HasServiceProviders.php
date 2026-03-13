<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Concerns\Package;

trait HasServiceProviders
{
    public ?string $publishableProviderName = null;

    public function publishesServiceProvider(string $providerName): static
    {
        $this->publishableProviderName = $providerName;

        return $this;
    }
}
