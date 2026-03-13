<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Filament\Resources\AutomationRules\Pages;

use daacreators\CreatorsTicketing\Filament\Resources\AutomationRules\AutomationRuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAutomationRule extends CreateRecord
{
    protected static string $resource = AutomationRuleResource::class;
}
