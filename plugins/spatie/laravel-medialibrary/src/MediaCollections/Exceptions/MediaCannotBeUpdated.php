<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Exceptions;

use Exception;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaCannotBeUpdated extends Exception
{
    public static function doesNotBelongToCollection(string $collectionName, Media $media): self
    {
        return new self("Media id {$media->getKey()} is not part of collection `{$collectionName}`");
    }
}
