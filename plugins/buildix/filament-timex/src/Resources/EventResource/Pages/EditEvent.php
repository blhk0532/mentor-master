<?php

declare(strict_types=1);

namespace Buildix\Timex\Resources\EventResource\Pages;

use Buildix\Timex\Resources\EventResource;
use Buildix\Timex\Traits\TimexTrait;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Form;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    use TimexTrait;

    protected static string $resource = EventResource::class;

    public function form(Form $form): Form
    {
        return $form->schema(self::getResource()::getCreateEditForm());
    }

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
