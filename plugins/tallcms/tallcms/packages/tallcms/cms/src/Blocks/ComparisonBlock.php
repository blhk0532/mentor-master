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
use Tallcms\Pro\Traits\RequiresLicense;

class ComparisonBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use HasDaisyUIOptions;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-comparison';
    }

    public static function getLabel(): string
    {
        return 'Comparison (Pro)';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Side-by-side feature comparison')
            ->modalHeading('Configure Comparison Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Comparison Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-arrows-right-left')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('Compare Plans'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Find the plan that fits your needs')
                                            ->rows(2),
                                    ]),

                                Section::make('Comparison Columns')
                                    ->schema([
                                        Repeater::make('columns')
                                            ->label('Columns')
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label('Column Title')
                                                    ->required(),

                                                Toggle::make('highlighted')
                                                    ->label('Highlight as Recommended')
                                                    ->default(false),
                                            ])
                                            ->minItems(2)
                                            ->maxItems(5)
                                            ->defaultItems(2)
                                            ->default([
                                                ['title' => 'Basic', 'highlighted' => false],
                                                ['title' => 'Pro', 'highlighted' => true],
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => ($state['title'] ?? 'Column').(($state['highlighted'] ?? false) ? ' ★' : '')),
                                    ]),

                                Section::make('Feature Rows')
                                    ->schema([
                                        Repeater::make('features')
                                            ->label('Features')
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Feature Name')
                                                    ->required()
                                                    ->columnSpanFull(),

                                                Repeater::make('values')
                                                    ->label('Column Values')
                                                    ->schema([
                                                        Select::make('status')
                                                            ->label('Status')
                                                            ->options([
                                                                'check' => '✓ Included',
                                                                'x' => '✗ Not Included',
                                                                'partial' => '◐ Partial',
                                                                'custom' => 'Custom Text',
                                                            ])
                                                            ->default('check')
                                                            ->live(),

                                                        TextInput::make('text')
                                                            ->label('Custom Text')
                                                            ->visible(fn (Get $get): bool => $get('status') === 'custom'),
                                                    ])
                                                    ->minItems(2)
                                                    ->maxItems(5)
                                                    ->defaultItems(2)
                                                    ->columns(2)
                                                    ->collapsible()
                                                    ->itemLabel(fn (array $state): string => match ($state['status'] ?? 'check') {
                                                        'check' => '✓ Included',
                                                        'x' => '✗ Not Included',
                                                        'partial' => '◐ Partial',
                                                        'custom' => $state['text'] ?? 'Custom',
                                                        default => 'Value',
                                                    }),
                                            ])
                                            ->defaultItems(5)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Feature'),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Display Options')
                                    ->schema([
                                        Select::make('style')
                                            ->label('Style')
                                            ->options([
                                                'default' => 'Default',
                                                'cards' => 'Cards',
                                                'minimal' => 'Minimal',
                                            ])
                                            ->default('default'),

                                        static::getColorSelect('highlight_color', 'Highlight Color', 'primary'),
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

    /**
     * Migrate old schema format to new format for backwards compatibility.
     */
    protected static function migrateOldSchema(array $config): array
    {
        // If new schema already present, return as-is
        if (isset($config['columns'])) {
            return $config;
        }

        // Old format detected - migrate
        $config['columns'] = [
            ['title' => $config['column_a_title'] ?? 'Basic', 'highlighted' => false],
            ['title' => $config['column_b_title'] ?? 'Pro', 'highlighted' => true],
        ];

        // Convert features from old format
        $migratedFeatures = [];
        foreach ($config['features'] ?? [] as $feature) {
            $migratedFeatures[] = [
                'name' => $feature['feature'] ?? '',
                'values' => [
                    [
                        'status' => $feature['column_a'] ?? 'check',
                        'text' => $feature['column_a_text'] ?? '',
                    ],
                    [
                        'status' => $feature['column_b'] ?? 'check',
                        'text' => $feature['column_b_text'] ?? '',
                    ],
                ],
            ];
        }
        $config['features'] = $migratedFeatures;

        return $config;
    }

    public static function toPreviewHtml(array $config): string
    {
        $config = static::migrateOldSchema($config);
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        $html = view('tallcms-pro::blocks.comparison', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'columns' => $config['columns'] ?? [],
            'features' => $config['features'] ?? [],
            'style' => $config['style'] ?? 'default',
            'highlight_color' => $config['highlight_color'] ?? 'primary',
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
        $config = static::migrateOldSchema($config);
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        $html = view('tallcms-pro::blocks.comparison', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'columns' => $config['columns'] ?? [],
            'features' => $config['features'] ?? [],
            'style' => $config['style'] ?? 'default',
            'highlight_color' => $config['highlight_color'] ?? 'primary',
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
