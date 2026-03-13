<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class FileDoesNotExist extends FileCannotBeAdded
{
    public static function create(string $path): self
    {
        return new self("File `{$path}` does not exist");
    }
}
