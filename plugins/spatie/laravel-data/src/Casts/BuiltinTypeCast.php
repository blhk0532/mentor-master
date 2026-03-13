<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Casts;

use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class BuiltinTypeCast implements Cast, IterableItemCast
{
    /**
     * @param  'bool'|'int'|'float'|'array'|'string'  $type
     */
    public function __construct(
        private string $type,
    ) {}

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $this->runCast($value);
    }

    public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return $this->runCast($value);
    }

    private function runCast(mixed $value): mixed
    {
        return match ($this->type) {
            'bool' => $this->castToBool($value),
            'int' => (int) $value,
            'float' => (float) $value,
            'array' => (array) $value,
            'string' => (string) $value,
        };
    }

    private function castToBool(mixed $value): bool
    {
        if (! is_string($value)) {
            return (bool) $value;
        }

        return match (mb_strtolower($value)) {
            'true' => true,
            'false' => false,
            default => (bool) $value,
        };
    }
}
