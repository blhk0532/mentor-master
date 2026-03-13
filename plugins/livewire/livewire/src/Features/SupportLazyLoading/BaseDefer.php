<?php

declare(strict_types=1);

namespace Livewire\Features\SupportLazyLoading;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class BaseDefer extends LivewireAttribute
{
    public function __construct(
        public $isolate = null,
        public $bundle = null,
    ) {}
}
