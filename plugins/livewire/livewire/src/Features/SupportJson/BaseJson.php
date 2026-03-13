<?php

declare(strict_types=1);

namespace Livewire\Features\SupportJson;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
class BaseJson extends LivewireAttribute
{
    public function dehydrate($context)
    {
        $methodName = $this->getName();

        $context->pushMemo('json', $methodName);
        $context->pushMemo('async', $methodName);
    }
}
