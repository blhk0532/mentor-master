<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class RequestDoesNotHaveFile extends FileCannotBeAdded
{
    public static function create(string $key): self
    {
        return new self("The current request does not have a file in a key named `{$key}`");
    }
}
