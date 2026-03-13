<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Filament\Resources\AutomationRules\Schemas;

use Filament\Schemas\Schema;

class AutomationRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
