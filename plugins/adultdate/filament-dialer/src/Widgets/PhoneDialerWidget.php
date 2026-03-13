<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class PhoneDialerWidget extends Widget
{
    public function render(): View
    {
        return view('filament-dialer::widgets.phone-dialer');
    }

    public function getColumnSpan(): int|array|string
    {
        return 'full';
    }
}
