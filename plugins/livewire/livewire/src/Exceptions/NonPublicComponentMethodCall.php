<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class NonPublicComponentMethodCall extends Exception
{
    use BypassViewHandler;

    public function __construct($method)
    {
        parent::__construct('Component method not found: ['.$method.']');
    }
}
