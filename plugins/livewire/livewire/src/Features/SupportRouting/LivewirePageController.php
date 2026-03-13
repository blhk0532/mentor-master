<?php

declare(strict_types=1);

namespace Livewire\Features\SupportRouting;

class LivewirePageController
{
    public function __invoke()
    {
        $component = request()->route()->action['livewire_component'];

        $instance = is_object($component)
            ? $component
            : app('livewire')->new($component);

        return app()->call([$instance, '__invoke']);
    }
}
