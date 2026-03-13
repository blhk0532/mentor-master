<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class DiskDoesNotExist extends FileCannotBeAdded
{
    public static function create(string $diskName): self
    {
        return new self("There is no filesystem disk named `{$diskName}`");
    }
}
