<?php

declare(strict_types=1);

namespace Hexters\HexaLite\Helpers;

use Illuminate\Support\Str;

trait UuidGenerator
{
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function bootUuidGenerator()
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid7();
        });
    }
}
