<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Exceptions;

use Exception;

class PaginatedCollectionIsAlwaysWrapped extends Exception
{
    public static function create(): self
    {
        return new self('A paginated data collection is always wrapped');
    }
}
