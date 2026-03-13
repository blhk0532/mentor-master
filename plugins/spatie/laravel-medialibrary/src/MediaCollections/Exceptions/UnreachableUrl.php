<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class UnreachableUrl extends FileCannotBeAdded
{
    public static function create(string $url): self
    {
        return new self("Url `{$url}` cannot be reached");
    }
}
