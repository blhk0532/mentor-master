<?php

declare(strict_types=1);

namespace Livewire\Features\SupportAttributes;

use BackedEnum;
use Exception;
use Livewire\Component;
use ReflectionNamedType;
use ReflectionProperty;

abstract class Attribute
{
    protected Component $component;

    protected $subTarget;

    protected $subName;

    protected AttributeLevel $level;

    protected $levelName;

    final public function __boot($component, AttributeLevel $level, $name = null, $subName = null, $subTarget = null)
    {
        $this->component = $component;
        $this->subName = $subName;
        $this->subTarget = $subTarget;
        $this->level = $level;
        $this->levelName = $name;
    }

    final public function getComponent()
    {
        return $this->component;
    }

    final public function getSubTarget()
    {
        return $this->subTarget;
    }

    final public function getSubName()
    {
        return $this->subName;
    }

    final public function getLevel()
    {
        return $this->level;
    }

    final public function getName()
    {
        return $this->levelName;
    }

    final public function getValue()
    {
        if ($this->level !== AttributeLevel::PROPERTY) {
            throw new Exception('Can\'t get the value of a non-property attribute.');
        }

        return data_get($this->component->all(), $this->levelName);
    }

    final public function setValue($value, ?bool $nullable = false)
    {
        if ($this->level !== AttributeLevel::PROPERTY) {
            throw new Exception('Can\'t set the value of a non-property attribute.');
        }

        if ($enum = $this->tryingToSetStringOrIntegerToEnum($value)) {
            if ($nullable) {
                $value = $enum::tryFrom($value);
            } else {
                $value = $enum::from($value);
            }
        }

        data_set($this->component, $this->levelName, $value);
    }

    protected function tryingToSetStringOrIntegerToEnum($subject)
    {
        if (! is_string($subject) && ! is_int($subject)) {
            return;
        }

        $target = $this->subTarget ?? $this->component;

        $name = $this->subName ?? $this->levelName;

        $property = str($name)->before('.')->toString();

        $reflection = new ReflectionProperty($target, $property);

        $type = $reflection->getType();

        // If the type is available, display its name
        if ($type instanceof ReflectionNamedType) {
            $name = $type->getName();

            // If the type is a BackedEnum then return it's name
            if (is_subclass_of($name, BackedEnum::class)) {
                return $name;
            }
        }

        return false;
    }
}
