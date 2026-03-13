<?php

namespace Tallcms\Pro\Blocks;

use Filament\Actions\Action;
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
use Tallcms\Pro\Traits\RequiresLicense;

class VideoBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-video';
    }

    public static function getLabel(): string
    {
        return 'Video (Pro)';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Embed videos from YouTube, Vimeo, or self-hosted sources')
            ->modalHeading('Configure Video Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Video Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-video-camera')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('Watch Our Video'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Learn more about our product')
                                            ->rows(2),
                                    ]),

                                Section::make('Video Source')
                                    ->schema([
                                        Select::make('source')
                                            ->label('Video Source')
                                            ->options([
                                                'youtube' => 'YouTube',
                                                'vimeo' => 'Vimeo',
                                                'self_hosted' => 'Self-Hosted (URL)',
                                            ])
                                            ->default('youtube')
                                            ->live()
                                            ->required(),

                                        TextInput::make('youtube_url')
                                            ->label('YouTube URL')
                                            ->placeholder('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
                                            ->helperText('Paste any YouTube URL (watch, share, or embed)')
                                            ->url()
                                            ->regex('/^https?:\/\//')
                                            ->visible(fn (Get $get): bool => $get('source') === 'youtube'),

                                        TextInput::make('vimeo_url')
                                            ->label('Vimeo URL')
                                            ->placeholder('https://vimeo.com/123456789')
                                            ->helperText('Paste any Vimeo URL')
                                            ->url()
                                            ->regex('/^https?:\/\//')
                                            ->visible(fn (Get $get): bool => $get('source') === 'vimeo'),

                                        TextInput::make('video_url')
                                            ->label('Video URL')
                                            ->placeholder('https://example.com/video.mp4')
                                            ->helperText('Direct URL to MP4, WebM, or OGG file')
                                            ->url()
                                            ->regex('/^https?:\/\//')
                                            ->visible(fn (Get $get): bool => $get('source') === 'self_hosted'),

                                        TextInput::make('poster_url')
                                            ->label('Poster Image URL')
                                            ->placeholder('https://example.com/poster.jpg')
                                            ->helperText('Thumbnail shown before video plays')
                                            ->url()
                                            ->regex('/^https?:\/\//')
                                            ->visible(fn (Get $get): bool => $get('source') === 'self_hosted'),
                                    ]),

                                Section::make('Caption')
                                    ->schema([
                                        Textarea::make('caption')
                                            ->label('Video Caption')
                                            ->placeholder('Optional caption text below the video')
                                            ->rows(2),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Video Options')
                                    ->schema([
                                        Select::make('aspect_ratio')
                                            ->label('Aspect Ratio')
                                            ->options([
                                                '16:9' => '16:9 (Widescreen)',
                                                '4:3' => '4:3 (Standard)',
                                                '21:9' => '21:9 (Ultrawide)',
                                                '1:1' => '1:1 (Square)',
                                            ])
                                            ->default('16:9'),

                                        Select::make('width')
                                            ->label('Max Width')
                                            ->options([
                                                'full' => 'Full Width',
                                                'xl' => 'Extra Large (1280px)',
                                                'lg' => 'Large (1024px)',
                                                'md' => 'Medium (768px)',
                                            ])
                                            ->default('xl'),

                                        Toggle::make('autoplay')
                                            ->label('Autoplay')
                                            ->helperText('May be blocked by browsers')
                                            ->default(false),

                                        Toggle::make('muted')
                                            ->label('Muted')
                                            ->helperText('Required for autoplay in most browsers')
                                            ->default(false),

                                        Toggle::make('loop')
                                            ->label('Loop')
                                            ->default(false),

                                        Toggle::make('controls')
                                            ->label('Show Controls')
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

    public static function toPreviewHtml(array $config): string
    {
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        $html = view('tallcms-pro::blocks.video', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'source' => $config['source'] ?? 'youtube',
            'youtube_url' => $config['youtube_url'] ?? '',
            'vimeo_url' => $config['vimeo_url'] ?? '',
            'video_url' => $config['video_url'] ?? '',
            'poster_url' => $config['poster_url'] ?? '',
            'aspect_ratio' => $config['aspect_ratio'] ?? '16:9',
            'width' => $config['width'] ?? 'xl',
            'autoplay' => $config['autoplay'] ?? false,
            'muted' => $config['muted'] ?? false,
            'loop' => $config['loop'] ?? false,
            'controls' => $config['controls'] ?? true,
            'caption' => $config['caption'] ?? '',
            'is_preview' => true,
            'anchor_id' => static::getAnchorId($config, $config['heading'] ?? null),
            'css_classes' => static::getCssClasses($config),
            'contentWidthClass' => $widthConfig['class'],
            'contentPadding' => $widthConfig['padding'],
            'animation_type' => $animConfig['animation_type'],
            'animation_duration' => $animConfig['animation_duration'],
        ])->render();

        return static::wrapWithLicenseCheck($html);
    }

    public static function toHtml(array $config, array $data): string
    {
        $widthConfig = static::resolveWidthClass($config);
        $animConfig = static::getAnimationConfig($config);

        $html = view('tallcms-pro::blocks.video', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'source' => $config['source'] ?? 'youtube',
            'youtube_url' => $config['youtube_url'] ?? '',
            'vimeo_url' => $config['vimeo_url'] ?? '',
            'video_url' => $config['video_url'] ?? '',
            'poster_url' => $config['poster_url'] ?? '',
            'aspect_ratio' => $config['aspect_ratio'] ?? '16:9',
            'width' => $config['width'] ?? 'xl',
            'autoplay' => $config['autoplay'] ?? false,
            'muted' => $config['muted'] ?? false,
            'loop' => $config['loop'] ?? false,
            'controls' => $config['controls'] ?? true,
            'caption' => $config['caption'] ?? '',
            'is_preview' => false,
            'anchor_id' => static::getAnchorId($config, $config['heading'] ?? null),
            'css_classes' => static::getCssClasses($config),
            'contentWidthClass' => $widthConfig['class'],
            'contentPadding' => $widthConfig['padding'],
            'animation_type' => $animConfig['animation_type'],
            'animation_duration' => $animConfig['animation_duration'],
        ])->render();

        return static::wrapWithLicenseCheck($html);
    }
}
