<?php

namespace Tallcms\Pro\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use TallCms\Cms\Filament\Blocks\Concerns\HasAnimationOptions;
use TallCms\Cms\Filament\Blocks\Concerns\HasBlockIdentifiers;
use TallCms\Cms\Filament\Blocks\Concerns\HasContentWidth;
use Tallcms\Pro\Traits\HasDaisyUIOptions;
use Tallcms\Pro\Traits\HasIconSelection;
use Tallcms\Pro\Traits\RequiresLicense;

class CounterBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use HasDaisyUIOptions;
    use HasIconSelection;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-counter';
    }

    public static function getLabel(): string
    {
        return 'Counter (Pro)';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Animated number counters with labels')
            ->modalHeading('Configure Counter Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Counter Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-chart-bar')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('Our Impact'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Numbers that speak for themselves')
                                            ->rows(2),
                                    ]),

                                Section::make('Counters')
                                    ->schema([
                                        Repeater::make('counters')
                                            ->label('')
                                            ->schema([
                                                TextInput::make('value')
                                                    ->label('Number Value')
                                                    ->numeric()
                                                    ->required()
                                                    ->placeholder('1000'),

                                                TextInput::make('prefix')
                                                    ->label('Prefix')
                                                    ->placeholder('$'),

                                                TextInput::make('suffix')
                                                    ->label('Suffix')
                                                    ->placeholder('+'),

                                                TextInput::make('label')
                                                    ->label('Label')
                                                    ->required()
                                                    ->placeholder('Happy Customers'),

                                                TextInput::make('description')
                                                    ->label('Description')
                                                    ->placeholder('And growing every day'),

                                                TextInput::make('max_value')
                                                    ->label('Max Value (for radial/gauge)')
                                                    ->numeric()
                                                    ->placeholder('Leave empty to use default')
                                                    ->helperText('Override the default max for this counter'),

                                                Select::make('icon_type')
                                                    ->label('Icon Type')
                                                    ->options([
                                                        'none' => 'None',
                                                        'heroicon' => 'Heroicon',
                                                        'emoji' => 'Emoji',
                                                    ])
                                                    ->default('none')
                                                    ->live(),

                                                Select::make('icon')
                                                    ->label('Icon')
                                                    ->options(static::getCommonHeroicons())
                                                    ->searchable()
                                                    ->visible(fn (Get $get) => $get('icon_type') === 'heroicon'),

                                                TextInput::make('icon_emoji')
                                                    ->label('Emoji')
                                                    ->placeholder("\u{1F680}")
                                                    ->maxLength(10)
                                                    ->visible(fn (Get $get) => $get('icon_type') === 'emoji'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(4)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => ($state['prefix'] ?? '').($state['value'] ?? '0').($state['suffix'] ?? '').' - '.($state['label'] ?? 'Counter')),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Display Style')
                                    ->schema([
                                        Select::make('display_style')
                                            ->label('Counter Style')
                                            ->options([
                                                'classic' => 'Classic (Numbers)',
                                                'radial' => 'Radial Progress',
                                                'gauge' => 'Gauge Meter',
                                            ])
                                            ->default('classic')
                                            ->helperText('Radial and Gauge show progress toward max value')
                                            ->live(),

                                        TextInput::make('default_max')
                                            ->label('Default Max Value')
                                            ->numeric()
                                            ->default(100)
                                            ->helperText('Default maximum for percentage calculation')
                                            ->visible(fn (Get $get) => in_array($get('display_style'), ['radial', 'gauge'])),

                                        Select::make('radial_size')
                                            ->label('Circle Size')
                                            ->options([
                                                'sm' => 'Small (120px)',
                                                'md' => 'Medium (160px)',
                                                'lg' => 'Large (200px)',
                                            ])
                                            ->default('md')
                                            ->visible(fn (Get $get) => in_array($get('display_style'), ['radial', 'gauge'])),

                                        Select::make('stroke_width')
                                            ->label('Stroke Width')
                                            ->options([
                                                'thin' => 'Thin',
                                                'normal' => 'Normal',
                                                'thick' => 'Thick',
                                            ])
                                            ->default('normal')
                                            ->visible(fn (Get $get) => in_array($get('display_style'), ['radial', 'gauge'])),
                                    ])
                                    ->columns(4),

                                Section::make('Layout Options')
                                    ->schema([
                                        Select::make('columns')
                                            ->label('Columns')
                                            ->options([
                                                '2' => '2 Columns',
                                                '3' => '3 Columns',
                                                '4' => '4 Columns',
                                            ])
                                            ->default('4'),

                                        Select::make('style')
                                            ->label('Card Style')
                                            ->options([
                                                'default' => 'Default',
                                                'cards' => 'Cards',
                                                'minimal' => 'Minimal',
                                            ])
                                            ->default('default'),

                                        static::getColorSelect('number_color', 'Number Color', 'primary'),

                                        TextInput::make('duration')
                                            ->label('Animation Duration (ms)')
                                            ->numeric()
                                            ->default(2000)
                                            ->helperText('Time for counter animation'),
                                    ])
                                    ->columns(4),

                                Section::make('Number Formatting')
                                    ->schema([
                                        Select::make('number_format')
                                            ->label('Number Format')
                                            ->options([
                                                'plain' => 'Plain (1000)',
                                                'thousands' => 'Thousands (1,000)',
                                                'abbreviated' => 'Abbreviated (1K)',
                                            ])
                                            ->default('plain')
                                            ->helperText('How to display the final number'),
                                    ]),

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

        $html = view('tallcms-pro::blocks.counter', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'counters' => $config['counters'] ?? [],
            'columns' => $config['columns'] ?? '4',
            'style' => $config['style'] ?? 'default',
            'display_style' => $config['display_style'] ?? 'classic',
            'default_max' => $config['default_max'] ?? 100,
            'radial_size' => $config['radial_size'] ?? 'md',
            'stroke_width' => $config['stroke_width'] ?? 'normal',
            'number_color' => $config['number_color'] ?? 'primary',
            'number_format' => $config['number_format'] ?? 'plain',
            'duration' => $config['duration'] ?? 2000,
            'animation_type' => $animConfig['animation_type'],
            'animation_duration' => $animConfig['animation_duration'],
            'animation_stagger' => $animConfig['animation_stagger'],
            'animation_stagger_delay' => $animConfig['animation_stagger_delay'],
            'is_preview' => true,
            'anchor_id' => static::getAnchorId($config, $config['heading'] ?? null),
            'css_classes' => static::getCssClasses($config),
            'contentWidthClass' => $widthConfig['class'],
            'contentPadding' => $widthConfig['padding'],
        ])->render();

        return static::wrapWithLicenseCheck($html);
    }

    public static function toHtml(array $config, array $data): string
    {
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        $html = view('tallcms-pro::blocks.counter', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'counters' => $config['counters'] ?? [],
            'columns' => $config['columns'] ?? '4',
            'style' => $config['style'] ?? 'default',
            'display_style' => $config['display_style'] ?? 'classic',
            'default_max' => $config['default_max'] ?? 100,
            'radial_size' => $config['radial_size'] ?? 'md',
            'stroke_width' => $config['stroke_width'] ?? 'normal',
            'number_color' => $config['number_color'] ?? 'primary',
            'number_format' => $config['number_format'] ?? 'plain',
            'duration' => $config['duration'] ?? 2000,
            'animation_type' => $animConfig['animation_type'],
            'animation_duration' => $animConfig['animation_duration'],
            'animation_stagger' => $animConfig['animation_stagger'],
            'animation_stagger_delay' => $animConfig['animation_stagger_delay'],
            'is_preview' => false,
            'anchor_id' => static::getAnchorId($config, $config['heading'] ?? null),
            'css_classes' => static::getCssClasses($config),
            'contentWidthClass' => $widthConfig['class'],
            'contentPadding' => $widthConfig['padding'],
        ])->render();

        return static::wrapWithLicenseCheck($html);
    }
}
