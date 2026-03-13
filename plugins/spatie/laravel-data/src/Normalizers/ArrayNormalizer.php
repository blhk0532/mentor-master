<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Normalizers;

class ArrayNormalizer implements Normalizer
{
    public function normalize(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        return $value;
    }
}
