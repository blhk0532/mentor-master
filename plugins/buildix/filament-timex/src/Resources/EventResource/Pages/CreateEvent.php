<?php

declare(strict_types=1);

namespace Buildix\Timex\Resources\EventResource\Pages;

use Buildix\Timex\Resources\EventResource;
use Buildix\Timex\Traits\TimexTrait;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use TimexTrait;

    protected static string $resource = EventResource::class;

    public function form(Form $form): Form
    {
        return $form->schema(self::getResource()::getCreateEditForm());
    }
}
