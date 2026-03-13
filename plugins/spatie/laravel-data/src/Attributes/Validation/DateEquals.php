<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Attributes\Validation;

use Attribute;
use DateTimeInterface;
use Spatie\LaravelData\Support\Validation\References\ExternalReference;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class DateEquals extends StringValidationAttribute
{
    public function __construct(protected string|DateTimeInterface|ExternalReference $date) {}

    public static function keyword(): string
    {
        return 'date_equals';
    }

    public static function create(string ...$parameters): static
    {
        return parent::create(
            self::parseDateValue($parameters[0])
        );
    }

    public function parameters(): array
    {
        return [$this->date];
    }
}
