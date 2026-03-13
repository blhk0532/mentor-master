<?php

declare(strict_types=1);

namespace Adultdate\FilamentBooking\ValueObjects;

use Carbon\CarbonImmutable;

use function Adultdate\FilamentBooking\browser_date_to_app_date;

final readonly class FetchInfo
{
    public CarbonImmutable $start;

    public CarbonImmutable $end;

    private array $originalData;

    public function __construct(array $data)
    {
        $this->originalData = $data;

        $this->start = browser_date_to_app_date(CarbonImmutable::make($data['startStr']));
        $this->end = browser_date_to_app_date(CarbonImmutable::make($data['endStr']));
    }
}
