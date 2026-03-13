<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

use Exception;

class InvalidConversion extends Exception
{
    public static function unknownName(string $name): self
    {
        return new self("There is no conversion named `{$name}`");
    }
}
