<?php

declare(strict_types=1);

namespace Livewire\Features\SupportTransitions;

use function Livewire\store;

trait HandlesTransitions
{
    public function transition($type = null, $skip = false)
    {
        if ($type) {
            store($this)->set('transitionType', $type);
        }
        if ($skip) {
            store($this)->set('transitionSkip', true);
        }
    }

    public function skipTransition()
    {
        store($this)->set('transitionSkip', true);
    }
}
