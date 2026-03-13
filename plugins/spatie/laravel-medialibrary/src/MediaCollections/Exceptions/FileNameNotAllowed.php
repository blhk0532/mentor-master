<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class FileNameNotAllowed extends FileCannotBeAdded
{
    public static function create(string $orignalName, string $sanitizedName): self
    {
        return new self("The file name `{$orignalName}` was sanitized to `{$sanitizedName}`. This sanitized file name is not allowed because it is a PHP file.");
    }
}
