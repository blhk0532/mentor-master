<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Partials\Segments;

class FieldsPartialSegment extends PartialSegment
{
    public function __construct(public readonly array $fields) {}

    public function __toString(): string
    {
        return '{'.implode(',', $this->fields).'}';
    }
}
