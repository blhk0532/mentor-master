<?php

declare(strict_types=1);

namespace Spatie\Image\Exceptions;

use Exception;
use Throwable;

class UnsupportedImageFormat extends Exception
{
    public static function make(string $extension, ?Throwable $previous = null): self
    {
        return new self("Unsupported format `{$extension}`.", previous: $previous);
    }
}
