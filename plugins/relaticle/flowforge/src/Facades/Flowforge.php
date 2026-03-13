<?php

declare(strict_types=1);

namespace Relaticle\Flowforge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Relaticle\Flowforge\Flowforge
 */
class Flowforge extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Relaticle\Flowforge\Flowforge::class;
    }
}
