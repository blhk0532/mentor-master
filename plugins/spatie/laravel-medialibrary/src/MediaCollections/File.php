<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections;

use Stringable;

class File implements Stringable
{
    public function __construct(
        public string $name,
        public int $size,
        public string $mimeType
    ) {}

    public function __toString(): string
    {
        return "name: {$this->name}, size: {$this->size}, mime: {$this->mimeType}";
    }

    public static function createFromMedia($media): self
    {
        return new self($media->file_name, $media->size, $media->mime_type);
    }
}
