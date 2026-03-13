<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Timezone extends StringValidationAttribute
{
    public static function keyword(): string
    {
        return 'timezone';
    }

    public function parameters(): array
    {
        return [];
    }
}
