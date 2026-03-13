<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Lazy;

use Closure;
use Inertia\LazyProp;

class InertiaLazy extends ConditionalLazy
{
    public function __construct(
        Closure $value,
    ) {
        parent::__construct(fn () => true, $value);
    }

    public function resolve(): LazyProp
    {
        return new LazyProp($this->value);
    }
}
