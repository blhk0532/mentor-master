<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Partials\Segments;

class AllPartialSegment extends PartialSegment
{
    public function __toString(): string
    {
        return '*';
    }
}
