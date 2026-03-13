<?php

declare(strict_types=1);

namespace Livewire;

use Livewire\Drawer\Utils;
use WeakMap;

class ComponentHookRegistry
{
    private static $components;

    private static $componentHooks = [];

    public static function register($hook)
    {
        if (method_exists($hook, 'provide')) {
            $hook::provide();
        }

        if (in_array($hook, self::$componentHooks)) {
            return;
        }

        self::$componentHooks[] = $hook;
    }

    public static function getHook($component, $hook)
    {
        if (! isset(self::$components[$component])) {
            return;
        }

        $componentHooks = self::$components[$component];

        foreach ($componentHooks as $componentHook) {
            if ($componentHook instanceof $hook) {
                return $componentHook;
            }
        }
    }

    public static function boot()
    {
        self::$components = new WeakMap;

        foreach (self::$componentHooks as $hook) {
            on('mount', function ($component, $params, $key, $parent, $attributes) use ($hook) {
                if (! $hook = static::initializeHook($hook, $component)) {
                    return;
                }

                $hook->callBoot();
                $hook->callMount($params, $parent, $attributes);
            });

            on('hydrate', function ($component, $memo) use ($hook) {
                if (! $hook = static::initializeHook($hook, $component)) {
                    return;
                }

                $hook->callBoot();
                $hook->callHydrate($memo);
            });
        }

        on('update', function ($component, $fullPath, $newValue) {
            $propertyName = Utils::beforeFirstDot($fullPath);

            return static::proxyCallToHooks($component, 'callUpdate')($propertyName, $fullPath, $newValue);
        });

        on('call', function ($component, $method, $params, $componentContext, $earlyReturn, $metadata) {
            return static::proxyCallToHooks($component, 'callCall')($method, $params, $earlyReturn, $metadata, $componentContext);
        });

        on('render', function ($component, $view, $data) {
            return static::proxyCallToHooks($component, 'callRender')($view, $data);
        });

        on('renderIsland', function ($component, $name, $view, $data) {
            return static::proxyCallToHooks($component, 'callRenderIsland')($name, $view, $data);
        });

        on('dehydrate', function ($component, $context) {
            static::proxyCallToHooks($component, 'callDehydrate')($context);
        });

        on('destroy', function ($component, $context) {
            static::proxyCallToHooks($component, 'callDestroy')($context);
        });

        on('exception', function ($target, $e, $stopPropagation) {
            if ($target instanceof Component) {
                static::proxyCallToHooks($target, 'callException')($e, $stopPropagation);
            }
        });
    }

    public static function initializeHook($hook, $target)
    {
        if (! isset(self::$components[$target])) {
            self::$components[$target] = [];
        }

        $hook = new $hook;

        $hook->setComponent($target);

        // If no `skip` method has been implemented, then boot the hook anyway
        if (method_exists($hook, 'skip') && $hook->skip()) {
            return;
        }

        self::$components[$target][] = $hook;

        return $hook;
    }

    public static function proxyCallToHooks($target, $method)
    {
        return function (...$params) use ($target, $method) {
            $forwardCallbacks = [];

            foreach (static::$components[$target] ?? [] as $hook) {
                if ($callback = $hook->{$method}(...$params)) {
                    $forwardCallbacks[] = $callback;
                }
            }

            return function (...$forwards) use ($forwardCallbacks) {
                foreach ($forwardCallbacks as $callback) {
                    $callback(...$forwards);
                }
            };
        };
    }
}
