<?php

declare(strict_types=1);

namespace Livewire\Features\SupportLockedProperties;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[Attribute]
class BaseLocked extends LivewireAttribute
{
    public function update()
    {
        throw new CannotUpdateLockedPropertyException($this->getName());
    }
}
