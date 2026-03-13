<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Events;

use daacreators\CreatorsTicketing\Models\Ticket;
use daacreators\CreatorsTicketing\Models\TicketStatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public ?TicketStatus $oldStatus,
        public TicketStatus $newStatus,
        public mixed $changedBy
    ) {}
}
