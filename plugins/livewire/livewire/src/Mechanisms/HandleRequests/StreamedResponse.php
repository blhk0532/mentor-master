<?php

declare(strict_types=1);

namespace Livewire\Mechanisms\HandleRequests;

use Illuminate\Http\Response;

class StreamedResponse extends Response
{
    /**
     * Override to prevent header modification after streaming has started.
     */
    public function sendHeaders(?int $statusCode = null): static
    {
        return $this;
    }
}
