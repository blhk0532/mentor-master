<?php

declare(strict_types=1);

namespace Livewire\Features\SupportLifecycleHooks;

use Exception;
use Livewire\Exceptions\BypassViewHandler;

class DirectlyCallingLifecycleHooksNotAllowedException extends Exception
{
    use BypassViewHandler;

    public function __construct($method, $component)
    {
        parent::__construct(
            "Unable to call lifecycle method [{$method}] directly on component: [{$component}]"
        );
    }
}
