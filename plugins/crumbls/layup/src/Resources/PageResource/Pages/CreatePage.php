<?php

declare(strict_types=1);

namespace Crumbls\Layup\Resources\PageResource\Pages;

use Crumbls\Layup\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
