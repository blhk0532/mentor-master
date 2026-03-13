<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Normalizers;

use Spatie\LaravelData\Normalizers\Normalized\Normalized;

interface Normalizer
{
    public function normalize(mixed $value): null|array|Normalized;
}
