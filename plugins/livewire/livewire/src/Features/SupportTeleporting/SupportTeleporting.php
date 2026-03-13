<?php

declare(strict_types=1);

namespace Livewire\Features\SupportTeleporting;

use Illuminate\Support\Facades\Blade;
use Livewire\ComponentHook;

class SupportTeleporting extends ComponentHook
{
    public static function provide()
    {
        Blade::directive('teleport', function ($expression) {
            return '<template x-teleport="<?php echo e('.$expression.'); ?>">';
        });

        Blade::directive('endteleport', function () {
            return '</template>';
        });
    }
}
