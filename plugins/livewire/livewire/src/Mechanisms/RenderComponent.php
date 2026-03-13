<?php

declare(strict_types=1);

namespace Livewire\Mechanisms;

use Illuminate\Support\Facades\Blade;

class RenderComponent extends Mechanism
{
    public static function livewire($expression)
    {
        $key = null;
        $slots = null;

        // Extract key parameter
        $keyPattern = '/,\s*?key\(([\s\S]*)\)/'; // everything between ",key(" and ")"
        $expression = preg_replace_callback($keyPattern, function ($match) use (&$key) {
            $key = mb_trim($match[1]) ?: $key;

            return '';
        }, $expression);

        // Extract slots parameter (4th parameter)
        $slotsPattern = '/,\s*?(\$__slots\s*\?\?\s*\[\])/'; // match $__slots ?? []
        $expression = preg_replace_callback($slotsPattern, function ($match) use (&$slots) {
            $slots = mb_trim($match[1]);

            return '';
        }, $expression);

        if (is_null($key)) {
            $key = 'null';
        }

        if (is_null($slots)) {
            $slots = '[]';
        }

        $deterministicBladeKey = app(ExtendBlade\DeterministicBladeKeys::class)->generate();
        $deterministicBladeKey = "'{$deterministicBladeKey}'";

        return <<<EOT
<?php
\$__split = function (\$name, \$params = []) {
    return [\$name, \$params];
};
[\$__name, \$__params] = \$__split($expression);

\$key = $key;
\$__componentSlots = $slots;

\$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey($deterministicBladeKey, \$key);

\$__html = app('livewire')->mount(\$__name, \$__params, \$key, \$__componentSlots);

echo \$__html;

unset(\$__html);
unset(\$__name);
unset(\$__params);
unset(\$__componentSlots);
unset(\$__split);
if (isset(\$__slots)) unset(\$__slots);
?>
EOT;
    }

    public function boot()
    {
        Blade::directive('livewire', [self::class, 'livewire']);
    }
}
