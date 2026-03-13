<?php

namespace Tallcms\Pro\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Storage;
use TallCms\Cms\Filament\Blocks\Concerns\HasAnimationOptions;
use TallCms\Cms\Filament\Blocks\Concerns\HasBlockIdentifiers;
use TallCms\Cms\Filament\Blocks\Concerns\HasContentWidth;
use Tallcms\Pro\Traits\HasDaisyUIOptions;
use Tallcms\Pro\Traits\RequiresLicense;

class BeforeAfterBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use HasDaisyUIOptions;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-before-after';
    }

    public static function getLabel(): string
    {
        return 'Before/After (Pro)';
    }

    /**
     * Get text size options for text diff mode (responsive)
     */
    protected static function getTextSizeOptions(): array
    {
        return [
            'text-2xl lg:text-4xl' => 'Small',
            'text-4xl lg:text-6xl' => 'Medium',
            'text-6xl lg:text-8xl' => 'Large',
            'text-6xl lg:text-9xl' => 'Extra Large',
        ];
    }

    /**
     * Get background color options with content colors
     */
    protected static function getDiffBackgroundOptions(): array
    {
        return [
            'bg-primary text-primary-content' => 'Primary',
            'bg-secondary text-secondary-content' => 'Secondary',
            'bg-accent text-accent-content' => 'Accent',
            'bg-neutral text-neutral-content' => 'Neutral',
            'bg-info text-info-content' => 'Info',
            'bg-success text-success-content' => 'Success',
            'bg-warning text-warning-content' => 'Warning',
            'bg-error text-error-content' => 'Error',
            'bg-base-100 text-base-content' => 'Base Light',
            'bg-base-200 text-base-content' => 'Base Medium',
            'bg-base-300 text-base-content' => 'Base Dark',
        ];
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Interactive comparison slider for images or text')
            ->modalHeading('Configure Before/After Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Before/After Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-arrows-right-left')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('See the Difference'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Drag the slider to compare')
                                            ->rows(2),
                                    ]),

                                Section::make('Comparison Mode')
                                    ->schema([
                                        Select::make('mode')
                                            ->label('Comparison Type')
                                            ->options([
                                                'image' => 'Image Comparison',
                                                'text' => 'Text Comparison',
                                            ])
                                            ->default('image')
                                            ->live()
                                            ->helperText('Choose between comparing images or text'),
                                    ]),

                                // Image Mode Fields
                                Section::make('Before Image')
                                    ->schema([
                                        FileUpload::make('before_image')
                                            ->label('Before Image')
                                            ->image()
                                            ->directory('cms/blocks/before-after')
                                            ->disk(cms_media_disk())
                                            ->visibility(cms_media_visibility())
                                            ->imageEditor()
                                            ->helperText('The "before" image'),

                                        TextInput::make('before_label')
                                            ->label('Before Label')
                                            ->placeholder('Before')
                                            ->default('Before'),
                                    ])
                                    ->visible(fn (Get $get): bool => ($get('mode') ?? 'image') === 'image'),

                                Section::make('After Image')
                                    ->schema([
                                        FileUpload::make('after_image')
                                            ->label('After Image')
                                            ->image()
                                            ->directory('cms/blocks/before-after')
                                            ->disk(cms_media_disk())
                                            ->visibility(cms_media_visibility())
                                            ->imageEditor()
                                            ->helperText('The "after" image'),

                                        TextInput::make('after_label')
                                            ->label('After Label')
                                            ->placeholder('After')
                                            ->default('After'),
                                    ])
                                    ->visible(fn (Get $get): bool => ($get('mode') ?? 'image') === 'image'),

                                // Text Mode Fields
                                Section::make('Before State')
                                    ->schema([
                                        TextInput::make('before_text')
                                            ->label('Before Text')
                                            ->placeholder('BEFORE')
                                            ->default('BEFORE')
                                            ->maxLength(50),

                                        Select::make('before_style')
                                            ->label('Before Style')
                                            ->options(static::getDiffBackgroundOptions())
                                            ->default('bg-base-200 text-base-content'),
                                    ])
                                    ->columns(2)
                                    ->visible(fn (Get $get): bool => $get('mode') === 'text'),

                                Section::make('After State')
                                    ->schema([
                                        TextInput::make('after_text')
                                            ->label('After Text')
                                            ->placeholder('AFTER')
                                            ->default('AFTER')
                                            ->maxLength(50),

                                        Select::make('after_style')
                                            ->label('After Style')
                                            ->options(static::getDiffBackgroundOptions())
                                            ->default('bg-primary text-primary-content'),
                                    ])
                                    ->columns(2)
                                    ->visible(fn (Get $get): bool => $get('mode') === 'text'),

                                Section::make('Text Style')
                                    ->schema([
                                        Select::make('text_size')
                                            ->label('Text Size')
                                            ->options(static::getTextSizeOptions())
                                            ->default('text-4xl lg:text-6xl'),
                                    ])
                                    ->visible(fn (Get $get): bool => $get('mode') === 'text'),

                                Section::make('Caption')
                                    ->schema([
                                        Textarea::make('caption')
                                            ->label('Caption')
                                            ->placeholder('Optional description below the comparison')
                                            ->rows(2),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Display Options')
                                    ->schema([
                                        Select::make('width')
                                            ->label('Max Width')
                                            ->options([
                                                'full' => 'Full Width',
                                                'xl' => 'Extra Large (1280px)',
                                                'lg' => 'Large (1024px)',
                                                'md' => 'Medium (768px)',
                                            ])
                                            ->default('xl'),

                                        Toggle::make('show_labels')
                                            ->label('Show Labels')
                                            ->default(true),

                                        Toggle::make('rounded')
                                            ->label('Rounded Corners')
                                            ->default(true),
                                    ])
                                    ->columns(3),

                                Section::make('Appearance')
                                    ->schema([
                                        static::getContentWidthField(),
                                    ]),
                            ]),

                        static::getAnimationTab(),
                    ]),

                static::getIdentifiersSection(),
            ])->slideOver();
    }

    /**
     * Convert uploaded file path to URL using configured media disk
     */
    protected static function getImageUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // If it's already a full URL, return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Convert storage path to URL using configured media disk
        $disk = cms_media_disk();
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }

        return '';
    }

    /**
     * Build view data from config
     */
    protected static function buildViewData(array $config, bool $isPreview): array
    {
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        return [
            'mode' => $config['mode'] ?? 'image',
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            // Image mode
            'before_image' => static::getImageUrl($config['before_image'] ?? ''),
            'before_label' => $config['before_label'] ?? 'Before',
            'after_image' => static::getImageUrl($config['after_image'] ?? ''),
            'after_label' => $config['after_label'] ?? 'After',
            // Text mode
            'before_text' => $config['before_text'] ?? 'BEFORE',
            'before_style' => $config['before_style'] ?? 'bg-base-200 text-base-content',
            'after_text' => $config['after_text'] ?? 'AFTER',
            'after_style' => $config['after_style'] ?? 'bg-primary text-primary-content',
            'text_size' => $config['text_size'] ?? 'text-4xl lg:text-6xl',
            // Display options
            'width' => $config['width'] ?? 'xl',
            'show_labels' => $config['show_labels'] ?? true,
            'rounded' => $config['rounded'] ?? true,
            'caption' => $config['caption'] ?? '',
            'is_preview' => $isPreview,
            // Block identifiers
            'anchor_id' => static::getAnchorId($config, $config['heading'] ?? null),
            'css_classes' => static::getCssClasses($config),
            // Content width
            'contentWidthClass' => $widthConfig['class'],
            'contentPadding' => $widthConfig['padding'],
            // Animation
            'animation_type' => $animConfig['animation_type'],
            'animation_duration' => $animConfig['animation_duration'],
        ];
    }

    public static function toPreviewHtml(array $config): string
    {
        $html = view('tallcms-pro::blocks.before-after', static::buildViewData($config, true))->render();

        return static::wrapWithLicenseCheck($html);
    }

    public static function toHtml(array $config, array $data): string
    {
        $html = view('tallcms-pro::blocks.before-after', static::buildViewData($config, false))->render();

        return static::wrapWithLicenseCheck($html);
    }
}
