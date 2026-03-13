<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Partials\Segments;

class NestedPartialSegment extends PartialSegment
{
    public function __construct(public readonly string $field) {}

    public function __toString(): string
    {
        return $this->field;
    }
}
