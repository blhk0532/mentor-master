<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements HasCurrentTenantLabel
{
    public function getCurrentTenantLabel(): string
    {
        return 'Current team';
    }
}
