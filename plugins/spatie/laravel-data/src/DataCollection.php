<?php

declare(strict_types=1);

namespace Spatie\LaravelData;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;
use Spatie\LaravelData\Concerns\BaseDataCollectable;
use Spatie\LaravelData\Concerns\ContextableData;
use Spatie\LaravelData\Concerns\EnumerableMethods;
use Spatie\LaravelData\Concerns\IncludeableData;
use Spatie\LaravelData\Concerns\ResponsableData;
use Spatie\LaravelData\Concerns\TransformableData;
use Spatie\LaravelData\Concerns\WrappableData;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Contracts\BaseDataCollectable as BaseDataCollectableContract;
use Spatie\LaravelData\Contracts\ContextableData as ContextableDataContract;
use Spatie\LaravelData\Contracts\IncludeableData as IncludeableDataContract;
use Spatie\LaravelData\Contracts\ResponsableData as ResponsableDataContract;
use Spatie\LaravelData\Contracts\TransformableData as TransformableDataContract;
use Spatie\LaravelData\Contracts\WrappableData as WrappableDataContract;
use Spatie\LaravelData\Exceptions\CannotCastData;
use Spatie\LaravelData\Exceptions\InvalidDataCollectionOperation;
use Spatie\LaravelData\Support\EloquentCasts\DataCollectionEloquentCast;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements ArrayAccess<TKey, TValue>
 * @implements  IteratorAggregate<TKey, TValue>
 */
class DataCollection implements ArrayAccess, BaseDataCollectableContract, ContextableDataContract, Countable, IncludeableDataContract, IteratorAggregate, Responsable, ResponsableDataContract, TransformableDataContract, WrappableDataContract
{
    /** @use \Spatie\LaravelData\Concerns\BaseDataCollectable<TKey, TValue> */
    use BaseDataCollectable;

    use ContextableData;

    /** @use \Spatie\LaravelData\Concerns\EnumerableMethods<TKey, TValue> */
    use EnumerableMethods;

    use IncludeableData;
    use Macroable;
    use ResponsableData;
    use TransformableData;
    use WrappableData;

    /** @var Enumerable<TKey, TValue> */
    protected Enumerable $items;

    /**
     * @param  class-string<TValue>  $dataClass
     * @param  array|Enumerable<TKey, TValue>|DataCollection  $items
     */
    public function __construct(
        public readonly string $dataClass,
        Enumerable|array|self|null $items
    ) {
        if (is_array($items) || is_null($items)) {
            $items = new Collection($items);
        }

        if ($items instanceof self) {
            $items = $items->toCollection();
        }

        $this->items = $items->map(
            fn ($item) => $item instanceof $this->dataClass ? $item : $this->dataClass::from($item)
        );
    }

    public static function castUsing(array $arguments)
    {
        if (count($arguments) < 1) {
            throw CannotCastData::dataCollectionTypeRequired();
        }

        return new DataCollectionEloquentCast($arguments[0], self::class, array_slice($arguments, 1));
    }

    /**
     * @return array<TKey, TValue>
     */
    public function items(): array
    {
        return $this->items->all();
    }

    /**
     * @return Enumerable<TKey, TValue>
     */
    public function toCollection(): Enumerable
    {
        return $this->items;
    }

    /**
     * @param  TKey  $offset
     */
    public function offsetExists($offset): bool
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        return $this->items->offsetExists($offset);
    }

    /**
     * @param  TKey  $offset
     * @return TValue
     */
    public function offsetGet($offset): mixed
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $data = $this->items->offsetGet($offset);

        if ($data instanceof IncludeableDataContract
            && $data instanceof ContextableDataContract
        ) {
            $data->getDataContext()->mergePartials($this->getDataContext());
        }

        return $data;
    }

    /**
     * @param  TKey|null  $offset
     * @param  TValue  $value
     */
    public function offsetSet($offset, $value): void
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $value = $value instanceof BaseData
            ? $value
            : $this->dataClass::from($value);

        $this->items->offsetSet($offset, $value);
    }

    /**
     * @param  TKey  $offset
     */
    public function offsetUnset($offset): void
    {
        if (! $this->items instanceof ArrayAccess) {
            throw InvalidDataCollectionOperation::create();
        }

        $this->items->offsetUnset($offset);
    }
}
