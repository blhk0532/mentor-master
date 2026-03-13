<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class MissingRulesException extends Exception
{
    use BypassViewHandler;

    public function __construct($instance)
    {
        $class = $instance::class;

        parent::__construct(
            "Missing [\$rules/rules()] property/method on: [{$class}]."
        );
    }
}
