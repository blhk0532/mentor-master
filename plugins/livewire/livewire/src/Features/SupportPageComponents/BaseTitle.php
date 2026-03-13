<?php

declare(strict_types=1);

namespace Livewire\Features\SupportPageComponents;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[Attribute]
class BaseTitle extends LivewireAttribute
{
    public function __construct(
        public $content,
    ) {}
}
