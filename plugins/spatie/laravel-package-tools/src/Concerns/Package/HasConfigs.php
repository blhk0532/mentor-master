<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Concerns\Package;

trait HasConfigs
{
    public array $configFileNames = [];

    public function hasConfigFile($configFileName = null): static
    {
        $configFileName ??= $this->shortName();

        if (! is_array($configFileName)) {
            $configFileName = [$configFileName];
        }

        $this->configFileNames = $configFileName;

        return $this;
    }
}
