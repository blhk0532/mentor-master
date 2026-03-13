<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Events;

use daacreators\CreatorsTicketing\Models\Department;
use daacreators\CreatorsTicketing\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketTransferred
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public Department $oldDepartment,
        public Department $newDepartment,
        public mixed $transferredBy
    ) {}
}
