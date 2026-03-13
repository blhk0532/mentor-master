<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Validation;

class ValidationContext
{
    public function __construct(
        public mixed $payload,
        public mixed $fullPayload,
        public ValidationPath $path,
    ) {}
}
