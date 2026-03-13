<?php

declare(strict_types=1);

namespace Spatie\LaravelData;

use Closure;
use Countable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;
use Spatie\LaravelData\Concerns\BaseDataCollectable;
use Spatie\LaravelData\Concerns\ContextableData;
use Spatie\LaravelData\Concerns\IncludeableData;
use Spatie\LaravelData\Concerns\ResponsableData;
use Spatie\LaravelData\Concerns\TransformableData;
use Spatie\LaravelData\Concerns\WrappableData;
use Spatie\LaravelData\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use Spatie\LaravelData\Contracts\ContextableData as ContextableDataContract;
use Spatie\LaravelData\Contracts\IncludeableData as IncludeableDataContract;
use Spatie\LaravelData\Contracts\ResponsableData as ResponsableDataContract;
use Spatie\LaravelData\Contracts\TransformableData as TransformableDataContract;
use Spatie\LaravelData\Contracts\WrappableData as WrappableDataContract;
use Spatie\LaravelData\Exceptions\CannotCastData;
use Spatie\LaravelData\Exceptions\PaginatedCollectionIsAlwaysWrapped;
use Spatie\LaravelData\Support\EloquentCasts\DataCollectionEloquentCast;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements  IteratorAggregate<TKey, TValue>
 */
class CursorPaginatedDataCollection implements BaseDataCollectableContract, ContextableDataContract, Countable, IncludeableDataContract, IteratorAggregate, Responsable, ResponsableDataContract, TransformableDataContract, WrappableDataContract
{
    /** @use \Spatie\LaravelData\Concerns\BaseDataCollectable<TKey, TValue> */
    use BaseDataCollectable;

    use ContextableData;
    use IncludeableData;
    use Macroable;
    use ResponsableData;
    use TransformableData;
    use WrappableData;

    /** @var CursorPaginator<TValue> */
    protected CursorPaginator $items;

    /**
     * @param  class-string<TValue>  $dataClass
     * @param  CursorPaginator<TValue>  $items
     */
    public function __construct(
        public readonly string $dataClass,
        CursorPaginator $items
    ) {
        $this->items = $items->through(
            fn ($item) => $item instanceof $this->dataClass ? $item : $this->dataClass::from($item)
        );
    }

    public static function castUsing(array $arguments)
    {
        if (count($arguments) !== 1) {
            throw CannotCastData::dataCollectionTypeRequired();
        }

        return new DataCollectionEloquentCast(current($arguments));
    }

    /**
     * @param  Closure(TValue, TKey): TValue  $through
     * @return static<TKey, TValue>
     */
    public function through(Closure $through): static
    {
        $clone = clone $this;

        $clone->items = $clone->items->through($through);

        return $clone;
    }

    /**
     * @return CursorPaginator<TValue>
     */
    public function items(): CursorPaginator
    {
        return $this->items;
    }

    public function withoutWrapping(): static
    {
        throw PaginatedCollectionIsAlwaysWrapped::create();
    }
}
