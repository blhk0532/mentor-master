<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\MediaCollections\Contracts;

use Illuminate\Support\Collection;

interface MediaLibraryRequest
{
    public function mediaLibraryRequestItems(string $key): Collection;
}
