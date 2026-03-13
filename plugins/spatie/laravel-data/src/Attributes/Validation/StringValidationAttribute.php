<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Attributes\Validation;

abstract class StringValidationAttribute extends ValidationAttribute
{
    abstract public function parameters(): array;

    final public static function create(string ...$parameters): static
    {
        return new static(...$parameters);
    }
}
