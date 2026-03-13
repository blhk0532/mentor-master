<?php

declare(strict_types=1);

namespace Spatie\Image\Enums;

enum BorderType: string
{
    case Expand = 'expand';
    case Overlay = 'overlay';
    case Shrink = 'shrink';
}
