<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Partner;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class PartnerObserver
{
    /**
     * Handle the Partner "created" event.
     */
    public function created(Partner $partner): void
    {
        try {
            Cache::delete('partners_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the Partner "updated" event.
     */
    public function updated(Partner $partner): void
    {
        //
    }

    /**
     * Handle the Partner "deleted" event.
     */
    public function deleted(Partner $partner): void
    {
        try {
            Cache::delete('partners_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the Partner "restored" event.
     */
    public function restored(Partner $partner): void
    {
        //
    }

    /**
     * Handle the Partner "force deleted" event.
     */
    public function forceDeleted(Partner $partner): void
    {
        //
    }
}
