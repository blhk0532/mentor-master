<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class ComponentNotFoundException extends Exception
{
    use BypassViewHandler;
}
