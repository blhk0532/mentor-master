<?php

declare(strict_types=1);

namespace Statikbe\FilamentTranslationManager;

class FilamentTranslationManager
{
    public static array $locales;

    public static function setLocales(array $locales): void
    {
        self::$locales = $locales;
    }

    public static function getLocales(): array
    {
        return self::$locales;
    }
}
