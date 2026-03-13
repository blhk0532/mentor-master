<?php

declare(strict_types=1);

namespace Livewire\Mechanisms\HandleComponents;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

use function Livewire\store;

#[Attribute]
class BaseRenderless extends LivewireAttribute
{
    public function call()
    {
        store($this->component)->set('skipIslandsRender', true);

        $this->component->skipRender();
    }
}
