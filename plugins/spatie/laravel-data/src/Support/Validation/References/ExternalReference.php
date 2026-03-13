<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Validation\References;

interface ExternalReference
{
    public function getValue(): mixed;
}
