<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Wrapping;

enum WrapType: string
{
    case UseGlobal = 'use_global';
    case Disabled = 'disabled';
    case Defined = 'defined';
}
