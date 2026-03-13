<?php

declare(strict_types=1);

namespace Spatie\EloquentSortable;

use ArrayAccess;
use Illuminate\Database\Eloquent\Builder;

interface Sortable
{
    /**
     * This function reorders the records: the record with the first id in the array
     * will get order 1, the record with the second it will get order 2,...
     *
     * @param  array|ArrayAccess  $ids
     */
    public static function setNewOrder($ids, int $startOrder = 1): void;

    /**
     * Modify the order column value.
     */
    public function setHighestOrderNumber(): void;

    /**
     * Let's be nice and provide an ordered scope.
     *
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOrdered(Builder $query);

    /**
     * Determine if the order column should be set when saving a new model instance.
     */
    public function shouldSortWhenCreating(): bool;
}
