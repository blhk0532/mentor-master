<?php

declare(strict_types=1);

namespace Livewire\Attributes;

use Attribute;
use Livewire\Features\SupportValidation\BaseValidate;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_ALL)]
class Validate extends BaseValidate
{
    //
}
