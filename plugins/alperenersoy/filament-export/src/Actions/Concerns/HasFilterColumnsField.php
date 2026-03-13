<?php

namespace AlperenErsoy\FilamentExport\Actions\Concerns;

trait HasFilterColumnsField
{
    protected ?string $filterColumnsFieldLabel;

    public function filterColumnsFieldLabel(?string $label = null): static
    {
        $this->filterColumnsFieldLabel = $label;

        return $this;
    }

    public function getFilterColumnsFieldLabel(): ?string
    {
        return $this->filterColumnsFieldLabel;
    }
}
