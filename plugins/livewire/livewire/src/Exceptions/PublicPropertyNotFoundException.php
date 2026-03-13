<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class PublicPropertyNotFoundException extends Exception
{
    use BypassViewHandler;

    public function __construct($property, $component)
    {
        parent::__construct(
            "Unable to set component data. Public property [\${$property}] not found on component: [{$component}]"
        );
    }
}
