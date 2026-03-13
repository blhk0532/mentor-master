<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Casts;

interface Castable
{
    public static function dataCastUsing(array $arguments): Cast;
}
