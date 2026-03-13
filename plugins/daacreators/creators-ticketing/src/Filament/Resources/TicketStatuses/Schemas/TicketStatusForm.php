<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Filament\Resources\TicketStatuses\Schemas;

use Filament\Schemas\Schema;

class TicketStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
