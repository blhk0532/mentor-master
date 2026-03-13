<?php

namespace AlperenErsoy\FilamentExport\Actions\Concerns;

trait HasPageOrientationField
{
    protected ?string $pageOrientationFieldLabel;

    public function pageOrientationFieldLabel(?string $label = null): static
    {
        $this->pageOrientationFieldLabel = $label;

        return $this;
    }

    public function getPageOrientationFieldLabel(): ?string
    {
        return $this->pageOrientationFieldLabel;
    }
}
