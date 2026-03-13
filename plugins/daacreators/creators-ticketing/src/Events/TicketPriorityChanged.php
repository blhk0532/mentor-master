<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Events;

use daacreators\CreatorsTicketing\Enums\TicketPriority;
use daacreators\CreatorsTicketing\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketPriorityChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public TicketPriority $oldPriority,
        public TicketPriority $newPriority,
        public mixed $changedBy
    ) {}
}
