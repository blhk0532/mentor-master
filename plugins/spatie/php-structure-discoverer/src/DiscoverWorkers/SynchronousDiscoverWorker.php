<?php

declare(strict_types=1);

namespace Spatie\StructureDiscoverer\DiscoverWorkers;

use Illuminate\Support\Collection;
use Spatie\StructureDiscoverer\Data\DiscoveredStructure;
use Spatie\StructureDiscoverer\Data\DiscoverProfileConfig;

class SynchronousDiscoverWorker implements DiscoverWorker
{
    public function __construct() {}

    /**
     * @param  Collection<int, string>  $filenames
     * @return array<DiscoveredStructure>
     */
    public function run(Collection $filenames, DiscoverProfileConfig $config): array
    {
        return $config->structureParser->execute($filenames->toArray());
    }
}
