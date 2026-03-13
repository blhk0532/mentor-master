<?php

declare(strict_types=1);

namespace Spatie\StructureDiscoverer\DiscoverConditions;

use Closure;
use Spatie\StructureDiscoverer\Data\DiscoveredStructure;

class CustomDiscoverCondition extends DiscoverCondition
{
    public function __construct(protected Closure $closure) {}

    public function satisfies(DiscoveredStructure $discoveredData): bool
    {
        return ($this->closure)($discoveredData);
    }
}
