<?php

declare(strict_types=1);

namespace Spatie\StructureDiscoverer;

use Spatie\StructureDiscoverer\Cache\DiscoverCacheDriver;
use Spatie\StructureDiscoverer\Data\DiscoveredStructure;
use Spatie\StructureDiscoverer\Exceptions\StructureScoutsCacheDriverMissing;
use Spatie\StructureDiscoverer\Support\DiscoverCacheDriverFactory;
use Spatie\StructureDiscoverer\Support\LaravelDetector;

abstract class StructureScout
{
    abstract protected function definition(): Discover;

    final public static function create(): static
    {
        return new static;
    }

    final public function identifier(): string
    {
        return static::class;
    }

    final public function cacheDriver(): DiscoverCacheDriver
    {
        if (LaravelDetector::isRunningLaravel()) {
            return DiscoverCacheDriverFactory::create(config('structure-discoverer.cache'));
        }

        throw new StructureScoutsCacheDriverMissing;
    }

    /**
     * @return array<DiscoveredStructure>|array<string>
     */
    final public function get(): array
    {
        return $this->definition()
            ->withCache($this->identifier(), $this->cacheDriver())
            ->get();
    }

    /**
     * @return array<DiscoveredStructure>|array<string>
     */
    final public function cache(): array
    {
        return $this->definition()
            ->withCache($this->identifier(), $this->cacheDriver())
            ->cache();
    }

    final public function clear(): static
    {
        $this->cacheDriver()->forget($this->identifier());

        return $this;
    }

    final public function isCached(): bool
    {
        return $this->cacheDriver()->has($this->identifier());
    }
}
