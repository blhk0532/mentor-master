<?php

declare(strict_types=1);

namespace Spatie\LaravelData;

class Optional
{
    public static function create(): self
    {
        return new self;
    }
}
