<?php

declare(strict_types=1);

namespace Livewire\Features\SupportTransitions;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

use function Livewire\store;

#[Attribute(Attribute::TARGET_METHOD)]
class BaseTransitionAttribute extends LivewireAttribute
{
    public function __construct(
        public ?string $type = null,
        public bool $skip = false,
    ) {}

    public function call()
    {
        if ($this->type) {
            store($this->component)->set('transitionType', $this->type);
        }

        if ($this->skip) {
            store($this->component)->set('transitionSkip', true);
        }
    }
}
