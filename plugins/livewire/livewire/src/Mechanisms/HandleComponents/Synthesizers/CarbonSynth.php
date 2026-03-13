<?php

declare(strict_types=1);

namespace Livewire\Mechanisms\HandleComponents\Synthesizers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use DateTimeInterface;

class CarbonSynth extends Synth
{
    public static $types = [
        'native' => DateTimeImmutable::class,
        'nativeImmutable' => DateTimeImmutable::class,
        'carbon' => Carbon::class,
        'carbonImmutable' => CarbonImmutable::class,
        'illuminate' => \Illuminate\Support\Carbon::class,
    ];

    public static $key = 'cbn';

    public static function match($target)
    {
        foreach (self::$types as $type => $class) {
            if ($target instanceof $class) {
                return true;
            }
        }

        return false;
    }

    public static function matchByType($type)
    {
        return is_subclass_of($type, DateTimeInterface::class);
    }

    public static function hydrateFromType($type, $value)
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return new $type($value);
    }

    public function dehydrate($target)
    {
        return [
            $target->format(DateTimeInterface::ATOM),
            ['type' => array_search(get_class($target), self::$types)],
        ];
    }

    public function hydrate($value, $meta)
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return new self::$types[$meta['type']]($value);
    }
}
