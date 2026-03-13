<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class RootTagMissingFromViewException extends Exception
{
    use BypassViewHandler;

    public function __construct()
    {
        parent::__construct(
            'Livewire encountered a missing root tag when trying to render a '.
            "component. \n When rendering a Blade view, make sure it contains a root HTML tag."
        );
    }
}
