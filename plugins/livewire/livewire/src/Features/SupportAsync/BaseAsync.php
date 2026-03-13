<?php

declare(strict_types=1);

namespace Livewire\Features\SupportAsync;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[Attribute]
class BaseAsync extends LivewireAttribute
{
    public function dehydrate($context)
    {
        $methodName = $this->getName();

        $context->pushMemo('async', $methodName);
    }
}
