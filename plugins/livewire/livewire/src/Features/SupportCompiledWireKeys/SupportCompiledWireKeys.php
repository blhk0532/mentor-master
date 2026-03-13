<?php

declare(strict_types=1);

namespace Livewire\Features\SupportCompiledWireKeys;

use Illuminate\Support\Facades\Blade;
use Livewire\ComponentHook;

use function Livewire\on;

class SupportCompiledWireKeys extends ComponentHook
{
    public static $loopStack = [];

    public static $currentLoop = [
        'count' => null,
        'index' => null,
        'key' => null,
    ];

    public static function provide()
    {
        on('flush-state', function () {
            static::$loopStack = [];
            static::$currentLoop = [
                'count' => null,
                'index' => null,
                'key' => null,
            ];
        });

        if (! config('livewire.smart_wire_keys', true)) {
            return;
        }

        self::registerPrecompilers();
    }

    public static function registerPrecompilers()
    {
        Blade::precompiler(function ($contents) {
            $contents = static::compile($contents);

            return $contents;
        });
    }

    public static function compile($contents)
    {
        // Strip out all livewire tag components as we don't want to match any of them...
        $placeholder = '<__livewire-component-placeholder__>';
        $cleanedContents = preg_replace('/<livewire:[^>]+?\/>/is', $placeholder, $contents);

        // Handle `wire:key` attributes on elements...
        preg_match_all('/(?<=\s)wire:key\s*=\s*(?:"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"|\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\')/', $cleanedContents, $keys);

        foreach ($keys[0] as $index => $key) {
            $escapedKey = str_replace("'", "\'", $keys[1][$index]);
            $prefix = "<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('{$escapedKey}', get_defined_vars()); ?>";
            $contents = str_replace($key, $prefix.$key, $contents);
        }

        // Handle `wire:key` attributes on Blade components...
        $contents = preg_replace(
            '/(<\?php\s+\$component->withAttributes\(\[.*?\]\);\s*\?>)/s',
            "$1\n<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey(\$component); ?>\n",
            $contents
        );

        return $contents;
    }

    public static function openLoop()
    {
        if (self::$currentLoop['count'] === null) {
            self::$currentLoop['count'] = 0;
        } else {
            self::$currentLoop['count']++;
        }

        self::$loopStack[] = self::$currentLoop;

        self::$currentLoop = [
            'count' => null,
            'index' => null,
            'key' => null,
        ];
    }

    public static function startLoop($index)
    {
        self::$currentLoop['index'] = $index;
    }

    public static function endLoop()
    {
        self::$currentLoop = [
            'count' => null,
            'index' => null,
            'key' => null,
        ];
    }

    public static function closeLoop()
    {
        self::$currentLoop = array_pop(self::$loopStack);
    }

    public static function processElementKey($keyString, $data)
    {
        // If the key string matches an existing view name, return it as-is.
        // This prevents Blade::render() from incorrectly rendering a view
        // when the user just wants a literal string key like "account".
        if (view()->exists($keyString)) {
            $key = $keyString;
        } else {
            $key = Blade::render($keyString, $data);
        }

        self::$currentLoop['key'] = $key;
    }

    public static function processComponentKey($component)
    {
        if ($component->attributes->has('wire:key')) {
            self::$currentLoop['key'] = $component->attributes->get('wire:key');
        }
    }

    public static function generateKey($deterministicBladeKey, $key = null)
    {
        $finalKey = $deterministicBladeKey;

        $loops = array_merge(self::$loopStack, [self::$currentLoop]);

        foreach ($loops as $loop) {
            if (isset($loop['key']) || isset($loop['index'])) {
                $finalKey .= isset($loop['key'])
                    ? '-'.$loop['key']
                    : '-'.$loop['index'];
            }

            if (isset($loop['count'])) {
                $finalKey .= '-'.$loop['count'];
            }
        }

        if (isset($key) && $key !== '') {
            $finalKey .= '-'.$key;
        }

        return $finalKey;
    }
}
