<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support;

use Spatie\LaravelData\Contracts\BaseData;

class DataClassMorphMap
{
    /** @var array<string, class-string<BaseData>> */
    private array $map = [];

    /** @var array< class-string<BaseData>, string> */
    private array $reversedMap = [];

    /**
     * @param  class-string<BaseData>  $class
     */
    public function add(
        string $alias,
        string $class
    ): self {
        $this->map[$alias] = $class;
        $this->reversedMap[$class] = $alias;

        return $this;
    }

    /**
     * @param  array<string, class-string<BaseData>>  $map
     */
    public function merge(array $map): self
    {
        foreach ($map as $alias => $class) {
            $this->add($alias, $class);
        }

        return $this;
    }

    /**
     * @return class-string<BaseData>|null
     */
    public function getMorphedDataClass(string $alias): ?string
    {
        return $this->map[$alias] ?? null;
    }

    /**
     * @param  class-string<BaseData>  $class
     */
    public function getDataClassAlias(string $class): ?string
    {
        return $this->reversedMap[$class] ?? null;
    }
}
