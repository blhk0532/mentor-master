<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use AllowDynamicProperties;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Joaopaulolndev\FilamentWorldClock\Helpers\FlagsHelper;

#[AllowDynamicProperties]
class WorldClockWidget extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.world-clock-widget';

    protected array $cities = ['Europe/Stockholm'];

    protected static ?string $pollingInterval = '60s';

    public function __construct()
    {
        $timezones = [
            'Europe/Stockholm',
            'Asia/Bangkok',
        ];

        $times = [];
        foreach ($timezones as $timezone) {

            $time = Carbon::now($timezone);
            $name = explode('/', $time->getTimezone()->getName())[1];
            $name = str_replace('_', ' ', $name);
            $hour = (int) $time->format('H');

            $offset = $time->getOffset();
            $hours = intdiv($offset, 3600);
            $minutes = abs($offset % 3600 / 60);
            $gmtOffset = sprintf('GMT %+d:%02d', $hours, $minutes);

            $times[] = [
                'name' => __(ucwords($name)),
                'time' => $time->format('H:i'),
                'flag' => FlagsHelper::get($timezone),
                'night' => $hour > 17 || $hour <= 6 ? true : false,
                'timezone' => $gmtOffset,
            ];
        }

        $this->cities = $times;
    }

    public static function getSort(): int
    {

        return 1;
    }

    public function shouldShowTitle(): bool
    {

        return true;
    }

    public function title()
    {

        return Str::ucfirst(now()->locale('sv')->translatedFormat('l, d F Y'));
    }

    public function description()
    {

        return Str::ucfirst(now()->locale('th')->translatedFormat('l, d F Y'));
    }

    public function quantityPerRow()
    {

        return '1';
    }

    public function getColumnSpan(): int|string|array
    {

        return '1/2';

    }

    public function render(): View
    {
        return view($this->view, [
            'cities' => $this->cities,
            'shouldShowTitle' => $this->shouldShowTitle(),
            'title' => $this->title(),
            'description' => $this->description(),
            'quantityPerRow' => $this->quantityPerRow() ?? '1',
        ]);
    }
}
