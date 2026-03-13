<?php

declare(strict_types=1);

namespace Livewire\Features\SupportLegacyModels;

use Exception;
use Livewire\Exceptions\BypassViewHandler;

class CannotBindToModelDataWithoutValidationRuleException extends Exception
{
    use BypassViewHandler;

    public function __construct($key, $component)
    {
        parent::__construct(
            "Cannot bind property [$key] without a validation rule present in the [\$rules] array on Livewire component: [{$component}]."
        );
    }
}
