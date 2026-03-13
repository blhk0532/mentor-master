<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\Support\FileNamer;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class FileNamer
{
    abstract public function conversionFileName(string $fileName, Conversion $conversion): string;

    abstract public function responsiveFileName(string $fileName): string;

    final public function originalFileName(string $fileName): string
    {
        $extLength = mb_strlen(pathinfo($fileName, PATHINFO_EXTENSION));

        $baseName = mb_substr($fileName, 0, mb_strlen($fileName) - ($extLength ? $extLength + 1 : 0));

        return $baseName;
    }

    final public function temporaryFileName(Media $media, string $extension): string
    {
        return "{$this->responsiveFileName($media->file_name)}.{$extension}";
    }

    final public function extensionFromBaseImage(string $baseImage): string
    {
        return pathinfo($baseImage, PATHINFO_EXTENSION);
    }
}
