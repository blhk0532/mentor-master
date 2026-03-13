<?php

declare(strict_types=1);

namespace Livewire\Features\SupportTesting;

class ShowDuskComponent
{
    public function __invoke($component)
    {
        $class = urldecode($component);

        return app()->call(app('livewire')->new($class));
    }
}
