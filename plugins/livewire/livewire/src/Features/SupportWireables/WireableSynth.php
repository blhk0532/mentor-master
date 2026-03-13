<?php

declare(strict_types=1);

namespace Livewire\Features\SupportWireables;

use Exception;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Livewire\Wireable;

class WireableSynth extends Synth
{
    public static $key = 'wrbl';

    public static function match($target)
    {
        return is_object($target) && $target instanceof Wireable;
    }

    public static function unwrapForValidation($target)
    {
        return $target->toLivewire();
    }

    public function dehydrate($target, $dehydrateChild)
    {
        $data = $target->toLivewire();

        foreach ($data as $key => $child) {
            $data[$key] = $dehydrateChild($key, $child);
        }

        return [
            $data,
            ['class' => get_class($target)],
        ];
    }

    public function hydrate($value, $meta, $hydrateChild)
    {
        // Verify class implements Wireable even though checksum protects this...
        if (! isset($meta['class']) || ! is_a($meta['class'], Wireable::class, true)) {
            throw new Exception('Livewire: Invalid wireable class.');
        }

        foreach ($value as $key => $child) {
            $value[$key] = $hydrateChild($key, $child);
        }

        return $meta['class']::fromLivewire($value);
    }

    public function set(&$target, $key, $value)
    {
        $target->{$key} = $value;
    }
}
