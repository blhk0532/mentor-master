<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support;

class Skipped
{
    private static self $instance;

    private function __construct() {}

    public static function create(): self
    {
        return self::$instance ??= new self;
    }
}
