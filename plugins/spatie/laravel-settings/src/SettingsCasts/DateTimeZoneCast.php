<?php

declare(strict_types=1);

namespace Spatie\LaravelSettings\SettingsCasts;

use DateTimeZone;

class DateTimeZoneCast implements SettingsCast
{
    private string $type;

    public function __construct(?string $type)
    {
        $this->type = $type ?? DateTimeZone::class;
    }

    public function get($payload): ?DateTimeZone
    {
        return $payload !== null
            ? new DateTimeZone($payload)
            : null;
    }

    /**
     * @param  DateTimeZone|null  $payload
     */
    public function set($payload): ?string
    {
        return $payload !== null
            ? $payload->getName()
            : null;
    }
}
