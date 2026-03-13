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
use TallCms\Cms\Filament\Blocks\Concerns\HasAnimationOptions;
use TallCms\Cms\Filament\Blocks\Concerns\HasBlockIdentifiers;
use TallCms\Cms\Filament\Blocks\Concerns\HasContentWidth;
use Tallcms\Pro\Traits\RequiresLicense;

class CodeSnippetBlock extends RichContentCustomBlock
{
    use HasAnimationOptions;
    use HasBlockIdentifiers;
    use HasContentWidth;
    use RequiresLicense;

    public static function getId(): string
    {
        return 'pro-code-snippet';
    }

    public static function getLabel(): string
    {
        return 'Code Snippet (Pro)';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Syntax-highlighted code blocks using daisyUI mockup-code')
            ->modalHeading('Configure Code Snippet Block')
            ->modalWidth('4xl')
            ->schema([
                Tabs::make('Code Snippet Configuration')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-m-code-bracket')
                            ->schema([
                                Section::make('Header')
                                    ->schema([
                                        TextInput::make('heading')
                                            ->label('Section Heading')
                                            ->placeholder('Code Example'),

                                        Textarea::make('subheading')
                                            ->label('Subheading')
                                            ->placeholder('Here\'s how to implement this feature')
                                            ->rows(2),
                                    ]),

                                Section::make('Code')
                                    ->schema([
                                        Select::make('language')
                                            ->label('Language')
                                            ->options([
                                                'javascript' => 'JavaScript',
                                                'typescript' => 'TypeScript',
                                                'php' => 'PHP',
                                                'python' => 'Python',
                                                'html' => 'HTML',
                                                'css' => 'CSS',
                                                'scss' => 'SCSS/Sass',
                                                'json' => 'JSON',
                                                'yaml' => 'YAML',
                                                'bash' => 'Bash/Shell',
                                                'sql' => 'SQL',
                                                'markdown' => 'Markdown',
                                                'jsx' => 'JSX (React)',
                                                'tsx' => 'TSX (React)',
                                                'vue' => 'Vue',
                                                'go' => 'Go',
                                                'rust' => 'Rust',
                                                'java' => 'Java',
                                                'csharp' => 'C#',
                                                'cpp' => 'C++',
                                                'ruby' => 'Ruby',
                                                'swift' => 'Swift',
                                                'kotlin' => 'Kotlin',
                                                'docker' => 'Dockerfile',
                                                'nginx' => 'Nginx',
                                                'apache' => 'Apache',
                                                'plaintext' => 'Plain Text',
                                            ])
                                            ->default('javascript')
                                            ->searchable()
                                            ->required(),

                                        TextInput::make('filename')
                                            ->label('Filename')
                                            ->placeholder('app.js')
                                            ->helperText('Optional filename to display'),

                                        Textarea::make('code')
                                            ->label('Code')
                                            ->required()
                                            ->rows(12)
                                            ->placeholder('// Your code here'),
                                    ]),
                            ]),

                        Tab::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Section::make('Display Options')
                                    ->schema([
                                        Select::make('line_prefix')
                                            ->label('Line Prefix')
                                            ->options([
                                                'numbers' => 'Line Numbers (1, 2, 3...)',
                                                'dollar' => 'Dollar Sign ($)',
                                                'arrow' => 'Arrow (>)',
                                            ])
                                            ->default('numbers')
                                            ->helperText('Symbol shown before each line'),

                                        Toggle::make('show_line_numbers')
                                            ->label('Show Line Prefixes')
                                            ->default(true),

                                        Toggle::make('show_copy_button')
                                            ->label('Show Copy Button')
                                            ->default(true),

                                        Toggle::make('show_language_badge')
                                            ->label('Show Language Badge')
                                            ->default(true),

                                        Select::make('max_height')
                                            ->label('Max Height')
                                            ->options([
                                                'none' => 'No Limit',
                                                'sm' => 'Small (300px)',
                                                'md' => 'Medium (400px)',
                                                'lg' => 'Large (500px)',
                                                'xl' => 'Extra Large (600px)',
                                            ])
                                            ->default('none'),

                                        Select::make('theme')
                                            ->label('Theme')
                                            ->options([
                                                'default' => 'Default (Dark)',
                                                'light' => 'Light',
                                            ])
                                            ->default('default')
                                            ->helperText('Background theme for code block'),
                                    ])
                                    ->columns(3),

                                Section::make('Line Highlighting')
                                    ->schema([
                                        TextInput::make('highlight_lines')
                                            ->label('Highlight Lines')
                                            ->placeholder('1,3-5,10')
                                            ->helperText('Comma-separated line numbers or ranges (e.g., 1,3-5,10)'),
                                    ]),

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

        $html = view('tallcms-pro::blocks.code-snippet', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'language' => $config['language'] ?? 'javascript',
            'filename' => $config['filename'] ?? '',
            'code' => $config['code'] ?? '',
            'line_prefix' => $config['line_prefix'] ?? 'numbers',
            'show_line_numbers' => $config['show_line_numbers'] ?? true,
            'show_copy_button' => $config['show_copy_button'] ?? true,
            'show_language_badge' => $config['show_language_badge'] ?? true,
            'max_height' => $config['max_height'] ?? 'none',
            'theme' => $config['theme'] ?? 'default',
            'highlight_lines' => $config['highlight_lines'] ?? '',
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

        $html = view('tallcms-pro::blocks.code-snippet', [
            'heading' => $config['heading'] ?? '',
            'subheading' => $config['subheading'] ?? '',
            'language' => $config['language'] ?? 'javascript',
            'filename' => $config['filename'] ?? '',
            'code' => $config['code'] ?? '',
            'line_prefix' => $config['line_prefix'] ?? 'numbers',
            'show_line_numbers' => $config['show_line_numbers'] ?? true,
            'show_copy_button' => $config['show_copy_button'] ?? true,
            'show_language_badge' => $config['show_language_badge'] ?? true,
            'max_height' => $config['max_height'] ?? 'none',
            'theme' => $config['theme'] ?? 'default',
            'highlight_lines' => $config['highlight_lines'] ?? '',
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
