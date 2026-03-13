<?php

declare(strict_types=1);

namespace Spatie\Image\Enums;

enum FlipDirection: string
{
    case Horizontal = 'horizontal';
    case Vertical = 'vertical';
    case Both = 'both';
}
