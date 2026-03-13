<?php

namespace Tallcms\Pro\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use TallCms\Cms\Filament\Blocks\Concerns\HasAnimationOptions;
use TallCms\Cms\Filament\Blocks\Concerns\HasBlockIdentifiers;
use TallCms\Cms\Filament\Blocks\Concerns\HasContentWidth;
use Tallcms\Pro\Traits\HasDaisyUIOptions;
use Tallcms\Pro\Traits\RequiresLicense;

class TableBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use HasDaisyUIOptions;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-table';
    }

    public static function getLabel(): string
    {
        return 'Table (Pro)';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Create data tables with headers and rows')
            ->modalHeading('Configure Table Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Table Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-table-cells')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('Pricing Comparison'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Compare our plans side by side')
                                            ->rows(2),
                                    ]),

                                Section::make('Table Headers')
                                    ->schema([
                                        Repeater::make('headers')
                                            ->label('Column Headers')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->label('Header Label')
                                                    ->required(),

                                                Select::make('align')
                                                    ->label('Alignment')
                                                    ->options([
                                                        'left' => 'Left',
                                                        'center' => 'Center',
                                                        'right' => 'Right',
                                                    ])
                                                    ->default('left'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'Column'),
                                    ]),

                                Section::make('Table Rows')
                                    ->schema([
                                        Repeater::make('rows')
                                            ->label('Data Rows')
                                            ->schema([
                                                Repeater::make('cells')
                                                    ->label('Cells')
                                                    ->schema([
                                                        TextInput::make('value')
                                                            ->label('Cell Value')
                                                            ->required(),
                                                    ])
                                                    ->defaultItems(3)
                                                    ->grid(3),

                                                Toggle::make('highlight')
                                                    ->label('Highlight this row')
                                                    ->default(false),
                                            ])
                                            ->defaultItems(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['cells'][0]['value'] ?? 'Row'),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Table Options')
                                    ->schema([
                                        static::getSizeSelect('table_size', 'Table Size', 'md'),

                                        Toggle::make('striped')
                                            ->label('Striped Rows')
                                            ->default(true),

                                        Toggle::make('bordered')
                                            ->label('Show Borders')
                                            ->default(true),

                                        Toggle::make('hover')
                                            ->label('Hover Effect')
                                            ->default(true),

                                        Toggle::make('responsive')
                                            ->label('Responsive (scroll on mobile)')
                                            ->default(true),
                                    ])
                                    ->columns(5),

                                Section::make('Appearance')
                                    ->schema([
                                        static::getContentWidthField(),
                                    ]),
                            ]),

                        static::getAnimationTab(supportsStagger: true),
                    ]),

                static::getIdentifiersSection(),
            ])->slideOver();
    }

    public static function toPreviewHtml(array $config): string
    {
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        $html = view('tallcms-pro::blocks.table', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'headers' => $config['headers'] ?? [],
            'rows' => $config['rows'] ?? [],
            'table_size' => $config['table_size'] ?? 'md',
            'striped' => $config['striped'] ?? true,
            'bordered' => $config['bordered'] ?? true,
            'hover' => $config['hover'] ?? true,
            'responsive' => $config['responsive'] ?? true,
            'is_preview' => true,
            'anchor_id' => static::getAnchorId($config, $config['heading'] ?? null),
            'css_classes' => static::getCssClasses($config),
            'contentWidthClass' => $widthConfig['class'],
            'contentPadding' => $widthConfig['padding'],
            'animation_type' => $animConfig['animation_type'],
            'animation_duration' => $animConfig['animation_duration'],
            'animation_stagger' => $animConfig['animation_stagger'],
            'animation_stagger_delay' => $animConfig['animation_stagger_delay'],
        ])->render();

        return static::wrapWithLicenseCheck($html);
    }

    public static function toHtml(array $config, array $data): string
    {
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        $html = view('tallcms-pro::blocks.table', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'headers' => $config['headers'] ?? [],
            'rows' => $config['rows'] ?? [],
            'table_size' => $config['table_size'] ?? 'md',
            'striped' => $config['striped'] ?? true,
            'bordered' => $config['bordered'] ?? true,
            'hover' => $config['hover'] ?? true,
            'responsive' => $config['responsive'] ?? true,
            'is_preview' => false,
            'anchor_id' => static::getAnchorId($config, $config['heading'] ?? null),
            'css_classes' => static::getCssClasses($config),
            'contentWidthClass' => $widthConfig['class'],
            'contentPadding' => $widthConfig['padding'],
            'animation_type' => $animConfig['animation_type'],
            'animation_duration' => $animConfig['animation_duration'],
            'animation_stagger' => $animConfig['animation_stagger'],
            'animation_stagger_delay' => $animConfig['animation_stagger_delay'],
        ])->render();

        return static::wrapWithLicenseCheck($html);
    }
}
