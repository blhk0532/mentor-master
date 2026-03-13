<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Contracts;

interface EmptyData
{
    public static function empty(array $extra = [], mixed $replaceNullValuesWith = null): array;
}
