<?php

declare(strict_types=1);

namespace Hexters\HexaLite\Helpers;

use Illuminate\Support\Str;

trait UlidGenerator
{
    public function getRouteKeyName()
    {
        return 'ulid';
    }

    protected static function bootUlidGenerator()
    {
        static::creating(function ($model) {
            $model->ulid = (string) Str::ulid();
        });
    }
}
