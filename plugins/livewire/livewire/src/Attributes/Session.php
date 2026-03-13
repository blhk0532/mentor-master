<?php

declare(strict_types=1);

namespace Livewire\Attributes;

use Attribute;
use Livewire\Features\SupportSession\BaseSession;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Session extends BaseSession
{
    //
}
