<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class InvalidBase64Data extends FileCannotBeAdded
{
    public static function create(): self
    {
        return new self('Invalid base64 data provided');
    }
}
