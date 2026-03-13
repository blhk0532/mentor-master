<?php

namespace Anish\TextInputEntry\Infolists\Components;

use Closure;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\View\Components\TextEntryComponent\ItemComponent;
use Filament\Infolists\View\Components\TextEntryComponent\ItemComponent\IconComponent;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\TextSize;
use Filament\Support\Facades\FilamentColor;
use Illuminate\View\ComponentAttributeBag;

use function Filament\Support\generate_icon_html;

class TextInputEntry extends TextEntry
{
    protected string $view = 'text-input-entry::components.text-input-entry';

    protected ?Closure $updateStateUsing = null;

    /**
     * @var array<int|string, string|Closure>|string|Closure|null
     */
    protected array|string|Closure|null $rules = null;

    protected bool|Closure|null $editable = null;

    protected bool|Closure $showBorder = true;

    public function updateStateUsing(?Closure $callback): static
    {
        $this->updateStateUsing = $callback;

        return $this;
    }

    public function getUpdateStateUsing(): ?Closure
    {
        return $this->updateStateUsing;
    }

    /**
     * @param  array<int|string, string|Closure>|string|Closure|null  $rules
     */
    public function rules(array|string|Closure $rules): static
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array<int|string, string|Closure>|string|Closure|null
     */
    public function getRules(): array|string|Closure|null
    {
        return $this->rules;
    }

    public function editable(bool|Closure $editable): static
    {
        $this->editable = $editable;

        return $this;
    }

    public function isEditable(): bool
    {
        $record = $this->getRecord();

        if ($this->editable === null) {
            return true;
        }

        return $this->evaluate($this->editable, [
            'record' => $record,
        ]);
    }

    /**
     * Control whether to show border during edit.
     */
    public function border(bool|Closure $showBorder = true): static
    {
        $this->showBorder = $showBorder;

        return $this;
    }

    public function shouldShowBorder(): bool
    {
        return $this->evaluate($this->showBorder);
    }

    /**
     * Get the text size class for the input field.
     */
    public function getTextSizeClass(): string
    {
        $size = $this->getSize($this->getState());

        return match ($size) {
            TextSize::Small => 'text-sm',
            TextSize::Medium => 'text-base',
            TextSize::Large => 'text-lg',
            default => 'text-sm',
        };
    }

    /**
     * Get the text color classes for the input field using Filament's color system.
     */
    public function getTextColorClasses(): string
    {
        $color = $this->getColor($this->getState());

        if ($color === null) {
            return '';
        }

        if (is_array($color)) {
            return 'fi-color';
        }

        $colorClasses = FilamentColor::getComponentClasses(ItemComponent::class, $color);

        return implode(' ', $colorClasses);
    }

    /**
     * Get the text color styles for the input field when color is an array.
     */
    public function getTextColorStyles(): string
    {
        $color = $this->getColor($this->getState());

        if (! is_array($color)) {
            return '';
        }

        $styles = FilamentColor::getComponentCustomStyles(ItemComponent::class, $color);

        return implode('; ', array_map(fn ($key, $value) => "{$key}: {$value}", array_keys($styles), $styles));
    }

    /**
     * Get the icon HTML for the input field.
     */
    public function getIconHtml(): ?string
    {
        $state = $this->getState();
        $icon = $this->getIcon($state);
        $iconColor = $this->getIconColor($state);
        $size = $this->getSize($state);

        if ($icon === null) {
            return null;
        }

        $iconHtml = generate_icon_html(
            $icon,
            attributes: (new ComponentAttributeBag)
                ->color(IconComponent::class, $iconColor),
            size: match ($size) {
                TextSize::Medium => IconSize::Medium,
                TextSize::Large => IconSize::Large,
                default => IconSize::Small,
            }
        );

        return $iconHtml?->toHtml();
    }

    /**
     * Get the icon position.
     */
    public function getIconPositionValue(): IconPosition
    {
        return $this->getIconPosition();
    }
}
