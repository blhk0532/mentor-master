<?php

declare(strict_types=1);

namespace Adultdate\Schedule\Concerns;

use Adultdate\Schedule\Enums\Context;
use Adultdate\Schedule\ValueObjects\NoEventsClickInfo;

trait HandlesNoEventsClick
{
    protected bool $noEventsClickEnabled = false;

    public function isNoEventsClickEnabled(): bool
    {
        return $this->noEventsClickEnabled;
    }

    /**
     * @internal Do not override, internal purpose only. Use `onDateClick` instead
     */
    public function onNoEventsClickJs(array $data): void
    {
        // Check if no events click is enabled
        if (! $this->isNoEventsClickEnabled()) {
            return;
        }

        $this->setRawCalendarContextData(Context::NoEventsClick, $data);

        $this->onNoEventsClick($this->getCalendarContextInfo());
    }

    protected function onNoEventsClick(NoEventsClickInfo $info): void {}
}
