<?php

declare(strict_types=1);

namespace Livewire\Features\SupportEvents;

use function Livewire\store;

trait HandlesEvents
{
    protected $listeners = [];

    public function dispatch($event, ...$params)
    {
        $event = new Event($event, $params);

        store($this)->push('dispatched', $event);

        return $event;
    }

    protected function getListeners()
    {
        return $this->listeners;
    }
}
