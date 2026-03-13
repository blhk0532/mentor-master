<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Mappers;

interface NameMapper
{
    public function map(string|int $name): string|int;
}
