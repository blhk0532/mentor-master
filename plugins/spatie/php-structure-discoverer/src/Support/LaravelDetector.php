<?php

declare(strict_types=1);

namespace Spatie\StructureDiscoverer\Support;

use Illuminate\Foundation\Application;

class LaravelDetector
{
    private static ?bool $isRunningLaravel = null;

    public static function isRunningLaravel(): bool
    {
        return self::$isRunningLaravel ??= class_exists(Application::class);
    }
}
