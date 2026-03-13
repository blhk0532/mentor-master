<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

class MimeTypeNotAllowed extends FileCannotBeAdded
{
    public static function create(string $file, array $allowedMimeTypes): self
    {
        $mimeType = mime_content_type($file);

        $allowedMimeTypes = implode(', ', $allowedMimeTypes);

        return new self("File has a mime type of {$mimeType}, while only {$allowedMimeTypes} are allowed");
    }
}
