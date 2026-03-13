<?php

namespace Tallcms\Pro\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use TallCms\Cms\Filament\Blocks\Concerns\HasAnimationOptions;
use TallCms\Cms\Filament\Blocks\Concerns\HasBlockIdentifiers;
use TallCms\Cms\Filament\Blocks\Concerns\HasContentWidth;
use Tallcms\Pro\Traits\HasDaisyUIOptions;
use Tallcms\Pro\Traits\HasIconSelection;
use Tallcms\Pro\Traits\RequiresLicense;

class TabsBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use HasDaisyUIOptions;
    use HasIconSelection;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-tabs';
    }

    public static function getLabel(): string
    {
        return 'Tabs (Pro)';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Create tabbed content sections')
            ->modalHeading('Configure Tabs Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Tabs Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-rectangle-stack')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('Our Services'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Explore what we offer')
                                            ->rows(2),
                                    ]),

                                Section::make('Tab Items')
                                    ->schema([
                                        Repeater::make('tabs')
                                            ->label('')
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label('Tab Title')
                                                    ->required(),

                                                Select::make('icon')
                                                    ->label('Icon')
                                                    ->options(static::getCommonHeroicons())
                                                    ->searchable()
                                                    ->placeholder('Select icon (optional)'),

                                                RichEditor::make('content')
                                                    ->label('Tab Content')
                                                    ->required()
                                                    ->toolbarButtons([
                                                        'bold',
                                                        'italic',
                                                        'link',
                                                        'bulletList',
                                                        'orderedList',
                                                    ]),
                                            ])
                                            ->defaultItems(3)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Tab'),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Layout Options')
                                    ->schema([
                                        Select::make('layout')
                                            ->label('Tab Layout')
                                            ->options([
                                                'horizontal' => 'Horizontal',
                                                'vertical' => 'Vertical',
                                            ])
                                            ->default('horizontal'),

                                        Select::make('style')
                                            ->label('Style')
                                            ->options([
                                                'pills' => 'Pills',
                                                'underline' => 'Underline',
                                                'boxed' => 'Boxed',
                                            ])
                                            ->default('pills'),

                                        Select::make('alignment')
                                            ->label('Tab Alignment')
                                            ->options([
                                                'left' => 'Left',
                                                'center' => 'Center',
                                                'right' => 'Right',
                                                'full' => 'Full Width',
                                            ])
                                            ->default('left'),

                                        static::getSizeSelect('tab_size', 'Tab Size', 'md'),
                                    ])
                                    ->columns(4),

                                Section::make('Icon Options')
                                    ->schema([
                                        Select::make('icon_position')
                                            ->label('Icon Position')
                                            ->options([
                                                'left' => 'Left of Text',
                                                'top' => 'Above Text',
                                                'only' => 'Icon Only',
                                            ])
                                            ->default('left')
                                            ->helperText('How to display icons in tab buttons'),

                                        Select::make('active_indicator')
                                            ->label('Active Indicator')
                                            ->options([
                                                'default' => 'Default (Theme)',
                                                'underline' => 'Underline',
                                                'filled' => 'Filled Background',
                                            ])
                                            ->default('default')
                                            ->helperText('Visual indicator for active tab'),
                                    ])
                                    ->columns(2),

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

        $html = view('tallcms-pro::blocks.tabs', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'tabs' => $config['tabs'] ?? [],
            'layout' => $config['layout'] ?? 'horizontal',
            'style' => $config['style'] ?? 'pills',
            'alignment' => $config['alignment'] ?? 'left',
            'tab_size' => $config['tab_size'] ?? 'md',
            'icon_position' => $config['icon_position'] ?? 'left',
            'active_indicator' => $config['active_indicator'] ?? 'default',
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

        $html = view('tallcms-pro::blocks.tabs', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'tabs' => $config['tabs'] ?? [],
            'layout' => $config['layout'] ?? 'horizontal',
            'style' => $config['style'] ?? 'pills',
            'alignment' => $config['alignment'] ?? 'left',
            'tab_size' => $config['tab_size'] ?? 'md',
            'icon_position' => $config['icon_position'] ?? 'left',
            'active_indicator' => $config['active_indicator'] ?? 'default',
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
