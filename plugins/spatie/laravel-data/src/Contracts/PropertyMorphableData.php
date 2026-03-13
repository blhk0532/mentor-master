<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Contracts;

interface PropertyMorphableData
{
    public static function morph(array $properties): ?string;
}
