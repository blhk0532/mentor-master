<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Contracts;

interface BaseDataCollectable
{
    public function getDataClass(): string;
}
