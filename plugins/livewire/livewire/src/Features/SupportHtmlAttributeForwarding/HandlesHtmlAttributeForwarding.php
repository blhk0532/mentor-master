<?php

declare(strict_types=1);

namespace Livewire\Features\SupportHtmlAttributeForwarding;

trait HandlesHtmlAttributeForwarding
{
    protected array $htmlAttributes = [];

    public function withHtmlAttributes(array $attributes): self
    {
        $this->htmlAttributes = $attributes;

        return $this;
    }

    public function getHtmlAttributes(): array
    {
        return $this->htmlAttributes;
    }
}
