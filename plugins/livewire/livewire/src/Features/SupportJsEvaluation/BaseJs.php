<?php

declare(strict_types=1);

namespace Livewire\Features\SupportJsEvaluation;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[Attribute]
class BaseJs extends LivewireAttribute
{
    public function dehydrate($context)
    {
        $name = $this->getName();

        $stringifiedMethod = $this->component->$name();

        $context->pushEffect('js', $stringifiedMethod, $name);
    }
}
