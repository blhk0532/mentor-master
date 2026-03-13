<?php

namespace Tallcms\Cms\Traits;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

/**
 * Provides reusable daisyUI-compatible form schema components for Pro blocks.
 */
trait HasDaisyUIOptions
{
    /**
     * Get semantic color options for accents/highlights
     */
    protected static function getSemanticColorOptions(): array
    {
        return [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            'accent' => 'Accent',
            'neutral' => 'Neutral',
            'info' => 'Info',
            'success' => 'Success',
            'warning' => 'Warning',
            'error' => 'Error',
        ];
    }

    /**
     * Get background color options
     */
    protected static function getBackgroundOptions(): array
    {
        return [
            'bg-base-100' => 'Base (Default)',
            'bg-base-200' => 'Base Subtle',
            'bg-base-300' => 'Base Strong',
            'bg-primary' => 'Primary',
            'bg-secondary' => 'Secondary',
            'bg-accent' => 'Accent',
            'bg-neutral' => 'Neutral',
        ];
    }

    /**
     * Get padding options
     */
    protected static function getPaddingOptions(): array
    {
        return [
            'py-8 sm:py-12' => 'Small',
            'py-12 sm:py-16' => 'Medium (Default)',
            'py-16 sm:py-20' => 'Large',
            'py-20 sm:py-24' => 'Extra Large',
        ];
    }

    /**
     * Get component size options (for tabs, tables, etc.)
     */
    protected static function getSizeOptions(): array
    {
        return [
            'xs' => 'Extra Small',
            'sm' => 'Small',
            'md' => 'Medium (Default)',
            'lg' => 'Large',
        ];
    }

    /**
     * Get a reusable section appearance section
     */
    protected static function getSectionAppearanceSchema(
        string $defaultBackground = 'bg-base-100',
        string $defaultPadding = 'py-12 sm:py-16'
    ): array {
        return [
            Select::make('section_background')
                ->label('Section Background')
                ->options(static::getBackgroundOptions())
                ->default($defaultBackground),

            Select::make('section_padding')
                ->label('Section Padding')
                ->options(static::getPaddingOptions())
                ->default($defaultPadding),
        ];
    }

    /**
     * Get semantic color select field
     */
    protected static function getColorSelect(
        string $name = 'color',
        string $label = 'Color',
        string $default = 'primary'
    ): Select {
        return Select::make($name)
            ->label($label)
            ->options(static::getSemanticColorOptions())
            ->default($default);
    }

    /**
     * Get size select field
     */
    protected static function getSizeSelect(
        string $name = 'size',
        string $label = 'Size',
        string $default = 'md'
    ): Select {
        return Select::make($name)
            ->label($label)
            ->options(static::getSizeOptions())
            ->default($default);
    }
}
