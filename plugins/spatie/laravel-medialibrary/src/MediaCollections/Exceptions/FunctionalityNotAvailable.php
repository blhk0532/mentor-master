<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

use Exception;

class FunctionalityNotAvailable extends Exception
{
    public static function mediaLibraryProRequired(): self
    {
        return new self('You need to have media library pro installed to make this work.');
    }
}
