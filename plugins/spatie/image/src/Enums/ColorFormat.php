<?php

declare(strict_types=1);

namespace Spatie\Image\Enums;

enum ColorFormat: string
{
    case Rgba = 'rgba';
    case Hex = 'hex';
    case Int = 'int';
    case Object = 'object';
    case Array = 'array';
}
