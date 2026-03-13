<?php

declare(strict_types=1);

namespace Livewire\Attributes;

use Attribute;
use Livewire\Features\SupportTransitions\BaseTransitionAttribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Transition extends BaseTransitionAttribute
{
    //
}
