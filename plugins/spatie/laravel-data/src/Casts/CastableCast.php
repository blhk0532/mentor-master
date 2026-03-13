<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Casts;

use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class CastableCast implements Cast
{
    private Cast $cast;

    /**
     * @param  class-string<Castable>  $castableClass
     */
    public function __construct(
        public string $castableClass,
        public array $arguments
    ) {}

    public function __serialize(): array
    {
        return [
            'castableClass' => $this->castableClass,
            'arguments' => $this->arguments,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->castableClass = $data['castableClass'];
        $this->arguments = $data['arguments'];
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (! isset($this->cast)) {
            $this->cast = $this->castableClass::dataCastUsing(...$this->arguments);
        }

        return $this->cast->cast($property, $value, $properties, $context);
    }
}
