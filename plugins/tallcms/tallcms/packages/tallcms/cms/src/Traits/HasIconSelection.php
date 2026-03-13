<?php

namespace Tallcms\Cms\Traits;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

trait HasIconSelection
{
    protected static function getIconSchema(
        string $prefix = 'icon',
        string $defaultType = 'none',
        bool $required = false
    ): array {
        return [
            Select::make("{$prefix}_type")
                ->label('Icon Type')
                ->options([
                    'none' => 'None',
                    'heroicon' => 'Heroicon',
                    'image' => 'Custom Image',
                    'emoji' => 'Emoji',
                ])
                ->default($defaultType)
                ->live(),

            TextInput::make("{$prefix}")
                ->label('Heroicon Name')
                ->placeholder('heroicon-o-plus')
                ->helperText('e.g., heroicon-o-check, heroicon-s-star')
                ->visible(fn (Get $get): bool => $get("{$prefix}_type") === 'heroicon')
                ->required(fn (Get $get): bool => $required && $get("{$prefix}_type") === 'heroicon'),

            FileUpload::make("{$prefix}_image")
                ->label('Icon Image')
                ->image()
                ->disk(cms_media_disk())
                ->directory('cms/pro/icons')
                ->visibility(cms_media_visibility())
                ->visible(fn (Get $get): bool => $get("{$prefix}_type") === 'image'),

            TextInput::make("{$prefix}_emoji")
                ->label('Emoji')
                ->placeholder("\u{1F680}")
                ->maxLength(10)
                ->visible(fn (Get $get): bool => $get("{$prefix}_type") === 'emoji'),
        ];
    }

    /**
     * Get a curated list of common Heroicons for icon selection dropdowns.
     */
    protected static function getCommonHeroicons(): array
    {
        return [
            'heroicon-o-check' => 'Check',
            'heroicon-o-check-circle' => 'Check Circle',
            'heroicon-o-x-mark' => 'X Mark',
            'heroicon-o-star' => 'Star',
            'heroicon-o-heart' => 'Heart',
            'heroicon-o-bolt' => 'Bolt',
            'heroicon-o-fire' => 'Fire',
            'heroicon-o-sparkles' => 'Sparkles',
            'heroicon-o-light-bulb' => 'Light Bulb',
            'heroicon-o-rocket-launch' => 'Rocket',
            'heroicon-o-shield-check' => 'Shield Check',
            'heroicon-o-lock-closed' => 'Lock',
            'heroicon-o-clock' => 'Clock',
            'heroicon-o-calendar' => 'Calendar',
            'heroicon-o-user' => 'User',
            'heroicon-o-users' => 'Users',
            'heroicon-o-cog-6-tooth' => 'Settings',
            'heroicon-o-document' => 'Document',
            'heroicon-o-folder' => 'Folder',
            'heroicon-o-chat-bubble-left' => 'Chat',
            'heroicon-o-envelope' => 'Email',
            'heroicon-o-phone' => 'Phone',
            'heroicon-o-map-pin' => 'Location',
            'heroicon-o-globe-alt' => 'Globe',
            'heroicon-o-currency-dollar' => 'Dollar',
            'heroicon-o-chart-bar' => 'Chart',
            'heroicon-o-arrow-trending-up' => 'Trending Up',
            'heroicon-o-trophy' => 'Trophy',
            'heroicon-o-gift' => 'Gift',
            'heroicon-o-academic-cap' => 'Education',
        ];
    }
}
