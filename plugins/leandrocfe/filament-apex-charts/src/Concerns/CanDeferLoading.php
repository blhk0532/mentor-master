<?php

declare(strict_types=1);

namespace Leandrocfe\FilamentApexCharts\Concerns;

trait CanDeferLoading
{
    public bool $readyToLoad = false;

    protected static bool $deferLoading = false;

    /**
     * Loads the widget.
     */
    public function loadWidget(): void
    {
        $this->readyToLoad = true;
    }

    /**
     * Retrieves the value of the static property $deferLoading.
     *
     * @return ?bool The value of the static property $deferLoading, or null if it is not set.
     */
    protected function getDeferLoading(): ?bool
    {
        return static::$deferLoading;
    }
}
