<?php

declare(strict_types=1);

namespace Livewire\Features\SupportComputed;

use Exception;
use Livewire\ComponentHook;
use Livewire\Drawer\Utils as SyntheticUtils;

use function Livewire\on;
use function Livewire\store;
use function Livewire\wrap;

class SupportLegacyComputedPropertySyntax extends ComponentHook
{
    protected static array $computedPropertyNamesCache = [];

    public static function provide()
    {
        on('__get', function ($target, $property, $returnValue) {
            if (static::hasComputedProperty($target, $property)) {
                $returnValue(static::getComputedProperty($target, $property));
            }
        });

        on('__unset', function ($target, $property) {
            if (static::hasComputedProperty($target, $property)) {
                store($target)->unset('computedProperties', $property);
            }
        });

        on('flush-state', function () {
            static::$computedPropertyNamesCache = [];
        });
    }

    public static function getComputedProperties($target)
    {
        return collect(self::getComputedPropertyNames($target))
            ->mapWithKeys(function ($property) use ($target) {
                return [$property => static::getComputedProperty($target, $property)];
            })
            ->all();
    }

    public static function hasComputedProperty($target, $property)
    {
        return in_array((string) str($property)->camel(), self::getComputedPropertyNames($target), true);
    }

    public static function getComputedProperty($target, $property)
    {
        if (! self::hasComputedProperty($target, $property)) {
            throw new Exception('No computed property found: $'.$property);
        }

        $method = 'get'.str($property)->studly().'Property';

        store($target)->push(
            'computedProperties',
            $value = store($target)->find('computedProperties', $property, fn () => wrap($target)->$method()),
            $property,
        );

        return $value;
    }

    public static function getComputedPropertyNames($target)
    {
        $className = get_class($target);

        if (isset(self::$computedPropertyNamesCache[$className])) {
            return self::$computedPropertyNamesCache[$className];
        }

        $methodNames = SyntheticUtils::getPublicMethodsDefinedBySubClass($target);

        $computedPropertyNames = collect($methodNames)
            ->filter(function ($method) {
                return str($method)->startsWith('get')
                    && str($method)->endsWith('Property');
            })
            ->map(function ($method) {
                return (string) str($method)->between('get', 'Property')->camel();
            })
            ->all();

        self::$computedPropertyNamesCache[$className] = $computedPropertyNames;

        return $computedPropertyNames;
    }
}
