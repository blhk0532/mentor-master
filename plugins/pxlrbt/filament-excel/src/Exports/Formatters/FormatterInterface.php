<?php

declare(strict_types=1);

namespace pxlrbt\FilamentExcel\Exports\Formatters;

interface FormatterInterface
{
    public function shouldApply($state): bool;

    public function format($state): string;
}
