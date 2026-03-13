<?php

declare(strict_types=1);

namespace Livewire\Features\SupportReactiveProps;

use Livewire\ComponentHook;

use function Livewire\after;
use function Livewire\on;
use function Livewire\trigger;

class SupportReactiveProps extends ComponentHook
{
    public static $pendingChildParams = [];

    public static $pendingUpdates = [];

    public static function provide()
    {
        on('flush-state', function () {
            static::$pendingChildParams = [];
            static::$pendingUpdates = [];
        });

        on('mount.stub', function ($tag, $id, $params, $parent, $key) {
            static::$pendingChildParams[$id] = $params;
        });

        // Process queued reactive prop updates AFTER all hooks have hydrated
        // This ensures SupportLifecycleHooks is initialized before we trigger updates
        after('hydrate', function ($component) {
            $id = $component->getId();
            $updates = static::$pendingUpdates[$id] ?? [];
            unset(static::$pendingUpdates[$id]);

            foreach ($updates as $update) {
                ['property' => $property, 'value' => $value, 'setValue' => $setValue] = $update;

                // Trigger updating* hooks (they see the old value)
                $finish = trigger('update', $component, $property, $value);

                // Set the new value on the component
                $setValue($value);

                // Trigger updated* hooks (they see the new value)
                $finish();
            }
        });
    }

    public static function hasPassedInProps($id)
    {
        return isset(self::$pendingChildParams[$id]);
    }

    public static function getPassedInProp($id, $name)
    {
        $params = self::$pendingChildParams[$id] ?? [];

        return $params[$name] ?? null;
    }

    public static function queueUpdate($id, $property, $value, $setValue)
    {
        self::$pendingUpdates[$id][] = [
            'property' => $property,
            'value' => $value,
            'setValue' => $setValue,
        ];
    }
}
