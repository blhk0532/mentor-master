<?php

declare(strict_types=1);

namespace Spatie\LaravelData;

use Spatie\LaravelData\Concerns\BaseData;
use Spatie\LaravelData\Concerns\ValidateableData;
use Spatie\LaravelData\Contracts\BaseData as BaseDataContract;
use Spatie\LaravelData\Contracts\ValidateableData as ValidateableDataContract;

class Dto implements BaseDataContract, ValidateableDataContract
{
    use BaseData;
    use ValidateableData;
}
