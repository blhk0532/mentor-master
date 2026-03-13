<?php

declare(strict_types=1);

namespace Spatie\TemporaryDirectory\Exceptions;

use Exception;

class PathAlreadyExists extends Exception
{
    public static function create(string $path): static
    {
        return new self("Path `{$path}` already exists.");
    }
}
