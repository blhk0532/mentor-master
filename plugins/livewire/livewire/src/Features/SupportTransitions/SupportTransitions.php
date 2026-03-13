<?php

declare(strict_types=1);

namespace Livewire\Features\SupportTransitions;

use Livewire\ComponentHook;

class SupportTransitions extends ComponentHook
{
    public function dehydrate($context)
    {
        $type = $this->storeGet('transitionType');
        $skip = $this->storeGet('transitionSkip');

        if ($type || $skip) {
            $context->addEffect('transition', array_filter([
                'type' => $type,
                'skip' => $skip,
            ]));
        }
    }
}
