<?php

declare(strict_types=1);

namespace Spatie\Image\Exceptions;

use Exception;

class InvalidFont extends Exception
{
    public static function make(string $font): self
    {
        return new self("Could not find a font file at `{$font}`");
    }
}
