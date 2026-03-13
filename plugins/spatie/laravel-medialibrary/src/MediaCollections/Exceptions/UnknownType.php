<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class UnknownType extends FileCannotBeAdded
{
    public static function create(): self
    {
        return new self('Only strings, FileObjects and UploadedFileObjects can be imported');
    }
}
