<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class MethodNotFoundException extends Exception
{
    use BypassViewHandler;

    public function __construct($method)
    {
        parent::__construct(
            "Unable to call component method. Public method [{$method}] not found on component"
        );
    }
}
