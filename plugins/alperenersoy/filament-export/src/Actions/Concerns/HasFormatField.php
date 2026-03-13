<?php

namespace AlperenErsoy\FilamentExport\Actions\Concerns;

trait HasFormatField
{
    protected ?string $formatFieldLabel;

    public function formatFieldLabel(?string $label = null): static
    {
        $this->formatFieldLabel = $label;

        return $this;
    }

    public function getFormatFieldLabel(): ?string
    {
        return $this->formatFieldLabel;
    }
}
