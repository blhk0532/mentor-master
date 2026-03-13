<?php

namespace AlperenErsoy\FilamentExport\Actions\Concerns;

trait HasFileNameField
{
    protected ?string $fileNameFieldLabel;

    public function fileNameFieldLabel(?string $label = null): static
    {
        $this->fileNameFieldLabel = $label;

        return $this;
    }

    public function getFileNameFieldLabel(): ?string
    {
        return $this->fileNameFieldLabel;
    }
}
