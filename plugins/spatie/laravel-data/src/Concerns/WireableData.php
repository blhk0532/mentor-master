<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Concerns;

use Spatie\LaravelData\Support\Creation\CreationContextFactory;

trait WireableData
{
    public static function fromLivewire($value): static
    {
        /** @var CreationContextFactory $factory */
        $factory = static::factory();

        return $factory
            ->ignoreMagicalMethod('fromLivewire')
            ->from($value);
    }

    public function toLivewire(): array
    {
        return $this->toArray();
    }
}
