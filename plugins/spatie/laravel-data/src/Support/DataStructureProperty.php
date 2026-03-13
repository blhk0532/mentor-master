<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support;

/**
 * @template T
 */
class DataStructureProperty
{
    /** @var T */
    private mixed $resolved;

    /**
     * @return T
     *
     * @note Will only be called when cached and thus already resolved
     */
    public function resolve()
    {
        return $this->resolved;
    }

    /**
     * @param  T  $resolved
     */
    public function setResolved(mixed $resolved): void
    {
        $this->resolved = $resolved;
    }
}
