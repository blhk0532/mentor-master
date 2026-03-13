<?php

declare(strict_types=1);

namespace Spatie\Image\Exceptions;

use Exception;

class ImageMethodDoesNotExist extends Exception
{
    public static function make(string $methodName): static
    {
        return new self("Method `{$methodName}` does not exist");
    }
}
