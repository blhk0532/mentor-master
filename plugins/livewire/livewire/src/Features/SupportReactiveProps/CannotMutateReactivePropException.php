<?php

declare(strict_types=1);

namespace Livewire\Features\SupportReactiveProps;

use Exception;

class CannotMutateReactivePropException extends Exception
{
    public function __construct($componentName, $propName)
    {
        parent::__construct("Cannot mutate reactive prop [{$propName}] in component: [{$componentName}]");
    }
}
