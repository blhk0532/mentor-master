<?php

declare(strict_types=1);

namespace Livewire\Features\SupportJsEvaluation;

use function Livewire\store;

trait HandlesJsEvaluation
{
    public function js($expression, ...$params)
    {
        store($this)->push('js', compact('expression', 'params'));
    }
}
