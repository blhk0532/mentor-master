<?php

declare(strict_types=1);

namespace Spatie\LaravelPackageTools\Exceptions;

use Exception;

class InvalidPackage extends Exception
{
    public static function nameIsRequired(): self
    {
        return new self('This package does not have a name. You can set one with `$package->name("yourName")`');
    }
}
