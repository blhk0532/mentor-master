<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Factories;

use ReflectionClass;
use ReflectionParameter;
use Spatie\LaravelData\Support\DataParameter;

class DataParameterFactory
{
    public function __construct(
        private DataTypeFactory $typeFactory,
    ) {}

    public function build(
        ReflectionParameter $reflectionParameter,
        ReflectionClass $reflectionClass,
    ): DataParameter {
        $hasDefaultValue = $reflectionParameter->isDefaultValueAvailable();

        return new DataParameter(
            $reflectionParameter->name,
            $reflectionParameter->isPromoted(),
            $hasDefaultValue,
            $hasDefaultValue ? $reflectionParameter->getDefaultValue() : null,
            $this->typeFactory->build(
                $reflectionParameter->getType(),
                $reflectionClass,
                $reflectionParameter,
            ),
        );
    }
}
