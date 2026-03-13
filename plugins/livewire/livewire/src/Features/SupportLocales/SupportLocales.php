<?php

declare(strict_types=1);

namespace Livewire\Features\SupportLocales;

use Livewire\ComponentHook;

class SupportLocales extends ComponentHook
{
    public function hydrate($memo)
    {
        if ($locale = $memo['locale']) {
            app()->setLocale($locale);
        }
    }

    public function dehydrate($context)
    {
        $context->addMemo('locale', app()->getLocale());
    }
}
