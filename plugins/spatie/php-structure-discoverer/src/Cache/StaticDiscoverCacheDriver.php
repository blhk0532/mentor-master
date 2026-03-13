<?php

declare(strict_types=1);

namespace Spatie\StructureDiscoverer\Cache;

class StaticDiscoverCacheDriver implements DiscoverCacheDriver
{
    /**
     * @var array<mixed>
     */
    public static array $entries = [];

    public static function clear(): void
    {
        self::$entries = [];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, self::$entries);
    }

    /**
     * @return array<mixed>
     */
    public function get(string $id): array
    {
        return self::$entries[$id];
    }

    /**
     * @param  array<mixed>  $discovered
     */
    public function put(string $id, array $discovered): void
    {
        self::$entries[$id] = $discovered;
    }

    public function forget(string $id): void
    {
        unset(self::$entries[$id]);
    }
}
