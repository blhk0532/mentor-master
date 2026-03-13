<?php

declare(strict_types=1);

namespace Spatie\StructureDiscoverer\DiscoverConditions;

use Spatie\StructureDiscoverer\Data\DiscoveredStructure;
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;

abstract class DiscoverCondition
{
    abstract public function satisfies(DiscoveredStructure $discoveredData): bool;

    final public static function create(): ConditionBuilder
    {
        return new ConditionBuilder;
    }
}
