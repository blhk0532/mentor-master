<?php

declare(strict_types=1);

namespace Spatie\TemporaryDirectory\Exceptions;

use Exception;

class InvalidDirectoryName extends Exception
{
    public static function create(string $directoryName): static
    {
        return new self("The directory name `{$directoryName}` contains invalid characters.");
    }
}
