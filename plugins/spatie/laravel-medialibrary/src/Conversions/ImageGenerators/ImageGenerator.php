<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\Conversions\ImageGenerators;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class ImageGenerator
{
    /*
     * This function should return a path to an image representation of the given file.
     */
    abstract public function convert(string $file, ?Conversion $conversion = null): ?string;

    abstract public function requirementsAreInstalled(): bool;

    abstract public function supportedExtensions(): Collection;

    abstract public function supportedMimeTypes(): Collection;

    final public function canConvert(Media $media): bool
    {
        if (! $this->requirementsAreInstalled()) {
            return false;
        }

        $validExtension = $this->canHandleExtension(mb_strtolower($media->extension));

        $validMimeType = $this->canHandleMime(mb_strtolower($media->mime_type));

        if ($this->shouldMatchBothExtensionsAndMimeTypes()) {
            return $validExtension && $validMimeType;
        }

        return $validExtension || $validMimeType;
    }

    final public function shouldMatchBothExtensionsAndMimeTypes(): bool
    {
        return false;
    }

    final public function canHandleMime(string $mime = ''): bool
    {
        return $this->supportedMimeTypes()->contains($mime);
    }

    final public function canHandleExtension(string $extension = ''): bool
    {
        return $this->supportedExtensions()->contains($extension);
    }

    final public function getType(): string
    {
        return mb_strtolower(class_basename(static::class));
    }
}
