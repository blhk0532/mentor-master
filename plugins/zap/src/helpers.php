<?php

declare(strict_types=1);

if (! function_exists('zap')) {
    /**
     * Get the Zap service instance.
     */
    function zap(): Zap\Services\ScheduleService
    {
        return app('zap');
    }
}
