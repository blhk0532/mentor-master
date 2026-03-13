<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class PropertyNotFoundException extends Exception
{
    use BypassViewHandler;

    public function __construct($property, $component)
    {
        parent::__construct(
            "Property [\${$property}] not found on component: [{$component}]"
        );
    }
}
