<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class ComponentAttributeMissingOnDynamicComponentException extends Exception
{
    use BypassViewHandler;

    public function __construct()
    {
        parent::__construct('Dynamic component tag is missing component attribute.');
    }
}
