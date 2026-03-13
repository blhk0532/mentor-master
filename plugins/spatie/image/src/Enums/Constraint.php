<?php

declare(strict_types=1);

namespace Spatie\Image\Enums;

enum Constraint: string
{
    case PreserveAspectRatio = 'preserveAspectRatio';
    case DoNotUpsize = 'upsize';
}
