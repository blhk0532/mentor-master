<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Enums;

enum DataCollectableType: string
{
    case Default = 'Default';
    case Array = 'Array';
    case Collection = 'Collection';
    case Paginated = 'Paginated';
    case CursorPaginated = 'CursorPaginated';
}
