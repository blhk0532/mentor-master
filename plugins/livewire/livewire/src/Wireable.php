<?php

declare(strict_types=1);

namespace Livewire;

interface Wireable
{
    public static function fromLivewire($value);

    public function toLivewire();
}
