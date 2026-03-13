<?php

declare(strict_types=1);

namespace Livewire\Exceptions;

use Exception;

class TooManyComponentsException extends Exception
{
    public function __construct(int $count, int $maxComponents)
    {
        $message = "Too many components in a single request ({$count}). "
            ."Maximum allowed is {$maxComponents}. "
            ."You can configure this limit in config/livewire.php under 'payload.max_components'.";

        parent::__construct($message);
    }
}
