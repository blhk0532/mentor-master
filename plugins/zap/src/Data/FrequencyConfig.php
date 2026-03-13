<?php

declare(strict_types=1);

namespace Zap\Data;

use Illuminate\Contracts\Support\Arrayable;
use Zap\Models\Schedule;

abstract class FrequencyConfig implements Arrayable
{
    abstract public static function fromArray(array $data): self;

    abstract public function getNextRecurrence(\Carbon\CarbonInterface $current): \Carbon\CarbonInterface;

    abstract public function shouldCreateInstance(\Carbon\CarbonInterface $date): bool;

    abstract public function shouldCreateRecurringInstance(Schedule $schedule, \Carbon\CarbonInterface $date): bool;

    final public function setStartFromStartDate(\Carbon\CarbonInterface $startDate): self
    {
        return $this;
    }

    final public function toArray(): array
    {
        return get_object_vars($this);
    }
}
