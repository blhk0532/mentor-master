<?php

declare(strict_types=1);

namespace JeffersonGoncalves\Filament\WhatsappWidget\Resources\WhatsappAgentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\Filament\WhatsappWidget\Resources\WhatsappAgentResource;

class CreateWhatsappAgent extends CreateRecord
{
    protected static string $resource = WhatsappAgentResource::class;
}
