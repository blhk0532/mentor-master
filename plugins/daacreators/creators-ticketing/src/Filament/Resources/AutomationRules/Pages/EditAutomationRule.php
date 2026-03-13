<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Filament\Resources\AutomationRules\Pages;

use daacreators\CreatorsTicketing\Filament\Resources\AutomationRules\AutomationRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAutomationRule extends EditRecord
{
    protected static string $resource = AutomationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
