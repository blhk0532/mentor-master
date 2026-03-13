<?php

declare(strict_types=1);

namespace Livewire\Mechanisms;

abstract class Mechanism
{
    final public function register()
    {
        app()->instance(static::class, $this);
    }

    final public function boot()
    {
        //
    }
}
