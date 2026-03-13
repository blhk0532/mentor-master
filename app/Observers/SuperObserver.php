<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Super;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class SuperObserver
{
    /**
     * Handle the Super "created" event.
     */
    public function created(Super $super): void
    {
        try {
            Cache::delete('supers_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the Super "updated" event.
     */
    public function updated(Super $super): void
    {
        //
    }

    /**
     * Handle the Super "deleted" event.
     */
    public function deleted(Super $super): void
    {
        try {
            Cache::delete('supers_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the Super "restored" event.
     */
    public function restored(Super $super): void
    {
        //
    }

    /**
     * Handle the Super "force deleted" event.
     */
    public function forceDeleted(Super $super): void
    {
        //
    }
}
