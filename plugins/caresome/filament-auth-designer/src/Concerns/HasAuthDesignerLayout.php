<?php

declare(strict_types=1);

namespace Caresome\FilamentAuthDesigner\Concerns;

use Caresome\FilamentAuthDesigner\AuthDesignerConfigRepository;
use Illuminate\Support\Facades\View;

trait HasAuthDesignerLayout
{
    abstract protected function getAuthDesignerPageKey(): string;

    public function boot(): void
    {
        if (method_exists(parent::class, 'boot')) {
            parent::boot();
        }

        static::$layout = 'filament-auth-designer::components.layouts.auth';

        $this->shareAuthDesignerConfig();
    }

    protected function shareAuthDesignerConfig(): void
    {
        $repository = app(AuthDesignerConfigRepository::class);
        $config = $repository->getConfig($this->getAuthDesignerPageKey());

        View::share('authDesignerConfig', $config);
    }
}
