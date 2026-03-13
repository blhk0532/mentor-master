<?php

declare(strict_types=1);

namespace Spatie\LaravelSettings\SettingsCasts;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Illuminate\Support\Carbon as IlluminateCarbon;

class DateTimeInterfaceCast implements SettingsCast
{
    private string $type;

    public function __construct(?string $type)
    {
        $this->type = $type ?? DateTimeImmutable::class;
    }

    public function get($payload): ?DateTimeInterface
    {
        if ($payload === null) {
            return null;
        }

        if ($this->type === Carbon::class) {
            return new Carbon($payload);
        }

        if ($this->type === CarbonImmutable::class) {
            return new CarbonImmutable($payload);
        }

        if ($this->type === IlluminateCarbon::class) {
            return new IlluminateCarbon($payload);
        }

        if ($this->type === DateTimeImmutable::class) {
            return new DateTimeImmutable($payload);
        }

        if ($this->type === DateTimeImmutable::class) {
            return new DateTimeImmutable($payload);
        }

        throw new Exception("Could not cast DateTime type `{$this->type}`");
    }

    /** @param DateTimeInterface|null $payload */
    public function set($payload): ?string
    {
        return $payload !== null
            ? $payload->format(DATE_ATOM)
            : null;
    }
}
