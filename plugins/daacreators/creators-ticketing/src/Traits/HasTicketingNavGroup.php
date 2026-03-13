<?php

declare(strict_types=1);

namespace daacreators\CreatorsTicketing\Traits;

trait HasTicketingNavGroup
{
    public static function getNavigationGroup(): ?string
    {
        return config('creators-ticketing.navigation_group', 'Ticketing');
    }
}
