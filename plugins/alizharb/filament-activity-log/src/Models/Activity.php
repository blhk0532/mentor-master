<?php

declare(strict_types=1);

namespace AlizHarb\ActivityLog\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

/**
 * Class Activity
 *
 * Custom Activity model extending Spatie's Activity model.
 */
class Activity extends SpatieActivity
{
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($activity) {
            $activity->properties = $activity->properties->merge([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}
