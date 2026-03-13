<?php

declare(strict_types=1);

namespace Livewire;

abstract class ComponentHook
{
    protected $component;

    final public function setComponent($component)
    {
        $this->component = $component;
    }

    final public function callBoot(...$params)
    {
        if (method_exists($this, 'boot')) {
            $this->boot(...$params);
        }
    }

    final public function callMount(...$params)
    {
        if (method_exists($this, 'mount')) {
            $this->mount(...$params);
        }
    }

    final public function callHydrate(...$params)
    {
        if (method_exists($this, 'hydrate')) {
            $this->hydrate(...$params);
        }
    }

    final public function callUpdate($propertyName, $fullPath, $newValue)
    {
        $callbacks = [];

        if (method_exists($this, 'update')) {
            $callbacks[] = $this->update($propertyName, $fullPath, $newValue);
        }

        return function (...$params) use ($callbacks) {
            foreach ($callbacks as $callback) {
                if (is_callable($callback)) {
                    $callback(...$params);
                }
            }
        };
    }

    final public function callCall($method, $params, $returnEarly, $metadata, $componentContext)
    {
        $callbacks = [];

        if (method_exists($this, 'call')) {
            $callbacks[] = $this->call($method, $params, $returnEarly, $metadata, $componentContext);
        }

        return function (...$params) use ($callbacks) {
            foreach ($callbacks as $callback) {
                if (is_callable($callback)) {
                    $callback(...$params);
                }
            }
        };
    }

    final public function callRender(...$params)
    {
        $callbacks = [];

        if (method_exists($this, 'render')) {
            $callbacks[] = $this->render(...$params);
        }

        return function (...$params) use ($callbacks) {
            foreach ($callbacks as $callback) {
                if (is_callable($callback)) {
                    $callback(...$params);
                }
            }
        };
    }

    final public function callRenderIsland(...$params)
    {
        $callbacks = [];

        if (method_exists($this, 'renderIsland')) {
            $callbacks[] = $this->renderIsland(...$params);
        }

        return function (...$params) use ($callbacks) {
            foreach ($callbacks as $callback) {
                if (is_callable($callback)) {
                    $callback(...$params);
                }
            }
        };
    }

    final public function callDehydrate(...$params)
    {
        if (method_exists($this, 'dehydrate')) {
            $this->dehydrate(...$params);
        }
    }

    final public function callDestroy(...$params)
    {
        if (method_exists($this, 'destroy')) {
            $this->destroy(...$params);
        }
    }

    final public function callException(...$params)
    {
        if (method_exists($this, 'exception')) {
            $this->exception(...$params);
        }
    }

    final public function getProperties()
    {
        return $this->component->all();
    }

    final public function getProperty($name)
    {
        return data_get($this->getProperties(), $name);
    }

    final public function storeSet($key, $value)
    {
        store($this->component)->set($key, $value);
    }

    final public function storePush($key, $value, $iKey = null)
    {
        store($this->component)->push($key, $value, $iKey);
    }

    final public function storeGet($key, $default = null)
    {
        return store($this->component)->get($key, $default);
    }

    final public function storeHas($key)
    {
        return store($this->component)->has($key);
    }
}
