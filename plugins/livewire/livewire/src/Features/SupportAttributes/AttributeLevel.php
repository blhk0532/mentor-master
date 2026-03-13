<?php

declare(strict_types=1);

namespace Livewire\Features\SupportAttributes;

enum AttributeLevel
{
    case ROOT;
    case PROPERTY;
    case METHOD;
}
