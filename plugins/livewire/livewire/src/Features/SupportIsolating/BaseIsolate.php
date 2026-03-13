<?php

declare(strict_types=1);

namespace Livewire\Features\SupportIsolating;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class BaseIsolate extends LivewireAttribute
{
    //
}
