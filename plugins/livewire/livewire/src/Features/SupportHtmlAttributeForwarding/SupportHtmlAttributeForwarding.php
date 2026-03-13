<?php

declare(strict_types=1);

namespace Livewire\Features\SupportHtmlAttributeForwarding;

use Illuminate\View\ComponentAttributeBag;
use Livewire\ComponentHook;

class SupportHtmlAttributeForwarding extends ComponentHook
{
    public function render($view, $properties)
    {
        $attributes = $this->component->getHtmlAttributes();

        $view->with(['attributes' => new ComponentAttributeBag($attributes)]);
    }

    public function renderIsland($name, $view, $properties)
    {
        $attributes = $this->component->getHtmlAttributes();

        $view->with(['attributes' => new ComponentAttributeBag($attributes)]);
    }

    public function hydrate($memo)
    {
        $attributes = $memo['attributes'] ?? [];

        if (! empty($attributes)) {
            $this->component->withHtmlAttributes($attributes);
        }
    }

    public function dehydrate($context)
    {
        $attributes = $this->component->getHtmlAttributes();

        if (! empty($attributes)) {
            $context->addMemo('attributes', $attributes);
        }
    }
}
