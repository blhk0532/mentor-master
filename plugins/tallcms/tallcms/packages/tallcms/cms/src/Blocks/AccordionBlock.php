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
use Filament\Schemas\Components\Utilities\Get;
use TallCms\Cms\Filament\Blocks\Concerns\HasAnimationOptions;
use TallCms\Cms\Filament\Blocks\Concerns\HasBlockIdentifiers;
use TallCms\Cms\Filament\Blocks\Concerns\HasContentWidth;
use Tallcms\Pro\Traits\HasDaisyUIOptions;
use Tallcms\Pro\Traits\HasIconSelection;
use Tallcms\Pro\Traits\RequiresLicense;

class AccordionBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use HasDaisyUIOptions;
    use HasIconSelection;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-accordion';
    }

    public static function getLabel(): string
    {
        return 'Accordion (Pro)';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Create collapsible accordion sections')
            ->modalHeading('Configure Accordion Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Accordion Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-bars-3-bottom-left')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('Frequently Asked Questions'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Find answers to common questions')
                                            ->rows(2),
                                    ]),

                                Section::make('Accordion Items')
                                    ->schema([
                                        Repeater::make('items')
                                            ->label('')
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label('Title')
                                                    ->required(),

                                                Textarea::make('content')
                                                    ->label('Content')
                                                    ->required()
                                                    ->rows(3),

                                                Toggle::make('show_icon')
                                                    ->label('Show Icon')
                                                    ->default(false)
                                                    ->live(),

                                                Select::make('icon')
                                                    ->label('Icon')
                                                    ->options(static::getCommonHeroicons())
                                                    ->searchable()
                                                    ->visible(fn (Get $get) => $get('show_icon')),
                                            ])
                                            ->defaultItems(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Item'),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Behavior')
                                    ->schema([
                                        Toggle::make('allow_multiple')
                                            ->label('Allow Multiple Open')
                                            ->helperText('Allow multiple items to be expanded at once')
                                            ->default(false),

                                        Toggle::make('first_open')
                                            ->label('First Item Open')
                                            ->helperText('Automatically expand the first item')
                                            ->default(true),

                                        Select::make('style')
                                            ->label('Style')
                                            ->options([
                                                'default' => 'Default (Card)',
                                                'bordered' => 'Bordered',
                                                'minimal' => 'Minimal',
                                            ])
                                            ->default('default'),
                                    ])
                                    ->columns(3),

                                Section::make('Collapse Icons')
                                    ->schema([
                                        Select::make('icon_style')
                                            ->label('Icon Style')
                                            ->options([
                                                'arrow' => 'Arrow (Default)',
                                                'plus-minus' => 'Plus/Minus',
                                                'chevron' => 'Chevron',
                                                'numbered' => 'Numbered (1, 2, 3)',
                                                'none' => 'No Icon',
                                            ])
                                            ->default('arrow')
                                            ->live(),

                                        Select::make('icon_position')
                                            ->label('Icon Position')
                                            ->options([
                                                'right' => 'Right (Default)',
                                                'left' => 'Left',
                                            ])
                                            ->default('right')
                                            ->visible(fn (Get $get) => ! in_array($get('icon_style'), ['arrow', 'none'])),

                                        static::getColorSelect('accent_color', 'Accent Color', 'primary'),
                                    ])
                                    ->columns(3),

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

        $html = view('tallcms-pro::blocks.accordion', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'items' => $config['items'] ?? [],
            'allow_multiple' => $config['allow_multiple'] ?? false,
            'first_open' => $config['first_open'] ?? true,
            'style' => $config['style'] ?? 'default',
            'icon_style' => $config['icon_style'] ?? 'arrow',
            'icon_position' => $config['icon_position'] ?? 'right',
            'accent_color' => $config['accent_color'] ?? 'primary',
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

        $html = view('tallcms-pro::blocks.accordion', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'items' => $config['items'] ?? [],
            'allow_multiple' => $config['allow_multiple'] ?? false,
            'first_open' => $config['first_open'] ?? true,
            'style' => $config['style'] ?? 'default',
            'icon_style' => $config['icon_style'] ?? 'arrow',
            'icon_position' => $config['icon_position'] ?? 'right',
            'accent_color' => $config['accent_color'] ?? 'primary',
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
