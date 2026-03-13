<?php

declare(strict_types=1);

namespace Livewire\Features\SupportMultipleRootElementDetection;

use Exception;
use Livewire\Exceptions\BypassViewHandler;

class MultipleRootElementsDetectedException extends Exception
{
    use BypassViewHandler;

    public function __construct($component)
    {
        parent::__construct('Livewire only supports one HTML element per component. Multiple root elements detected for component: ['.$component->getName().']');
    }
}
