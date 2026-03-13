<?php

namespace AlperenErsoy\FilamentExport\Actions\Concerns;

trait HasAdditionalColumnsField
{
    protected ?string $additionalColumnsFieldLabel;

    protected ?string $additionalColumnsTitleFieldLabel;

    protected ?string $additionalColumnsDefaultValueFieldLabel;

    protected ?string $additionalColumnsAddButtonLabel;

    public function additionalColumnsFieldLabel(?string $label = null): static
    {
        $this->additionalColumnsFieldLabel = $label;

        return $this;
    }

    public function getAdditionalColumnsFieldLabel(): ?string
    {
        return $this->additionalColumnsFieldLabel;
    }

    public function additionalColumnsTitleFieldLabel(?string $label = null): static
    {
        $this->additionalColumnsTitleFieldLabel = $label;

        return $this;
    }

    public function getAdditionalColumnsTitleFieldLabel(): ?string
    {
        return $this->additionalColumnsTitleFieldLabel;
    }

    public function additionalColumnsDefaultValueFieldLabel(?string $label = null): static
    {
        $this->additionalColumnsDefaultValueFieldLabel = $label;

        return $this;
    }

    public function getAdditionalColumnsDefaultValueFieldLabel(): ?string
    {
        return $this->additionalColumnsDefaultValueFieldLabel;
    }

    public function additionalColumnsAddButtonLabel(?string $label = null): static
    {
        $this->additionalColumnsAddButtonLabel = $label;

        return $this;
    }

    public function getAdditionalColumnsAddButtonLabel(): ?string
    {
        return $this->additionalColumnsAddButtonLabel;
    }
}
