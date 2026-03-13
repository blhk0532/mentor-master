<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Contracts;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Enumerable;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

/**
 * @template TValue
 */
interface DeprecatedData
{
    /**
     * @param  Enumerable<array-key, TValue>|TValue[]|AbstractPaginator|Paginator|AbstractCursorPaginator|CursorPaginator|DataCollection<array-key, TValue>  $items
     * @return ($items is AbstractCursorPaginator|\Illuminate\Pagination\CursorPaginator ? CursorPaginatedDataCollection<array-key, static> : ($items is \Illuminate\Pagination\Paginator|AbstractPaginator ? PaginatedDataCollection<array-key, static> : DataCollection<array-key, static>))
     */
    public static function collection(Enumerable|array|AbstractPaginator|Paginator|AbstractCursorPaginator|CursorPaginator|DataCollection $items): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection;
}
