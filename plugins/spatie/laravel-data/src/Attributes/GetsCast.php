<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Attributes;

use Spatie\LaravelData\Casts\Cast;

interface GetsCast
{
    public function get(): Cast;
}
