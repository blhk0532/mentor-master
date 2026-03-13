<?php

declare(strict_types=1);

namespace Livewire\Drawer;

use ReflectionObject;

class BaseUtils
{
    private static $reflectionCache = [];

    public static function isSyntheticTuple($payload)
    {
        return is_array($payload)
            && count($payload) === 2
            && isset($payload[1]['s']);
    }

    public static function isAPrimitive($target)
    {
        return
            is_numeric($target) ||
            is_string($target) ||
            is_bool($target) ||
            is_null($target);
    }

    public static function getPublicPropertiesDefinedOnSubclass($target)
    {
        $class = get_class($target);

        if (! isset(self::$reflectionCache[$class])) {
            self::$reflectionCache[$class] = self::reflectAndCachePropertyMetadata($target, function ($property) {
                return $property->getDeclaringClass()->getName() !== \Livewire\Component::class
                    && $property->getDeclaringClass()->getName() !== \Livewire\Volt\Component::class;
            });
        }

        return self::extractPropertyValuesFromInstance($target, self::$reflectionCache[$class]);
    }

    public static function getPublicProperties($target, $filter = null)
    {
        return collect((new ReflectionObject($target))->getProperties())
            ->filter(function ($property) {
                return $property->isPublic() && ! $property->isStatic() && $property->isDefault();
            })
            ->filter($filter ?? fn () => true)
            ->mapWithKeys(function ($property) use ($target) {
                // Ensures typed property is initialized in PHP >=7.4, if so, return its value,
                // if not initialized, return null (as expected in earlier PHP Versions)
                if (method_exists($property, 'isInitialized') && ! $property->isInitialized($target)) {
                    // If a type of `array` is given with no value, let's assume users want
                    // it prefilled with an empty array...
                    $value = (method_exists($property, 'getType') && $property->getType() && method_exists($property->getType(), 'getName') && $property->getType()->getName() === 'array')
                        ? [] : null;
                } else {
                    $value = $property->getValue($target);
                }

                return [$property->getName() => $value];
            })
            ->all();
    }

    public static function getPublicMethodsDefinedBySubClass($target)
    {
        $methods = array_filter((new ReflectionObject($target))->getMethods(), function ($method) {
            $isInBaseComponentClass = $method->getDeclaringClass()->getName() === \Livewire\Component::class || $method->getDeclaringClass()->getName() === \Livewire\Volt\Component::class;

            return $method->isPublic()
                && ! $method->isStatic()
                && ! $isInBaseComponentClass;
        });

        return array_map(function ($method) {
            return $method->getName();
        }, $methods);
    }

    public static function hasAttribute($target, $property, $attributeClass)
    {
        $property = self::getProperty($target, $property);

        foreach ($property->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();

            if ($instance instanceof $attributeClass) {
                return true;
            }
        }

        return false;
    }

    public static function getProperty($target, $property)
    {
        return (new ReflectionObject($target))->getProperty($property);
    }

    public static function propertyIsTyped($target, $property)
    {
        $property = self::getProperty($target, $property);

        return $property->hasType();
    }

    public static function propertyIsTypedAndUninitialized($target, $property)
    {
        $property = self::getProperty($target, $property);

        return $property->hasType() && (! $property->isInitialized($target));
    }

    private static function reflectAndCachePropertyMetadata($target, $filter = null)
    {
        return collect((new ReflectionObject($target))->getProperties())
            ->filter(function ($property) {
                return $property->isPublic() && ! $property->isStatic() && $property->isDefault();
            })
            ->filter($filter ?? fn () => true)
            ->mapWithKeys(function ($property) {
                $type = null;
                if (method_exists($property, 'getType') && $property->getType()) {
                    $type = method_exists($property->getType(), 'getName')
                        ? $property->getType()->getName()
                        : null;
                }

                return [$property->getName() => [
                    'name' => $property->getName(),
                    'type' => $type,
                ]];
            })
            ->all();
    }

    private static function extractPropertyValuesFromInstance($target, $cachedMetadata)
    {
        $properties = [];
        $reflection = new ReflectionObject($target); // One reflection object for all properties

        foreach ($cachedMetadata as $propertyName => $meta) {
            $property = $reflection->getProperty($propertyName);

            if (method_exists($property, 'isInitialized') && ! $property->isInitialized($target)) {
                $value = ($meta['type'] === 'array') ? [] : null;
            } else {
                $value = $property->getValue($target);
            }

            $properties[$propertyName] = $value;
        }

        return $properties;
    }
}
