<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class DiskCannotBeAccessed extends FileCannotBeAdded
{
    public static function create(string $diskName): self
    {
        return new self("Disk named `{$diskName}` cannot be accessed");
    }
}
