<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\Downloaders;

interface Downloader
{
    public function getTempFile(string $url): string;
}
