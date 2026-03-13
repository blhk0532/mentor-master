<?php

namespace TomatoPHP\FilamentNotes\Filament\Forms;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TomatoPHP\FilamentTypes\Models\Type;

class NoteForm
{
    public static function make(): array
    {
        return [
            Grid::make([
                'sm' => 1,
                'lg' => 1,
            ])->schema([
                Tabs::make()
                    ->tabs([
                        Tabs\Tab::make(trans('filament-notes::messages.tabs.general'))
                            ->schema([
                                TextInput::make('title')
                                    ->label(trans('filament-notes::messages.columns.title'))
                                    ->columnSpanFull(),
                                RichEditor::make('body')
                                    ->label(trans('filament-notes::messages.columns.body'))
                                    ->columnSpanFull(),
                                DatePicker::make('date')->label(trans('filament-notes::messages.columns.date'))
                                    ->native(false),
                                TimePicker::make('time')->label(trans('filament-notes::messages.columns.time'))
                                    ->native(false),
                                Toggle::make('is_pined')
                                    ->label(trans('filament-notes::messages.columns.is_pined'))
                                    ->columnSpanFull(),
                                Toggle::make('is_public')
                                    ->label(trans('filament-notes::messages.columns.is_public'))
                                    ->columnSpanFull(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2,
                            ]),
                        Tabs\Tab::make(trans('filament-notes::messages.tabs.style'))
                            ->schema([
                                IconPicker::make('icon')
                                    ->label(trans('filament-notes::messages.columns.icon'))
                                    ->columnSpanFull(),
                                ColorPicker::make('background')
                                    ->label(trans('filament-notes::messages.columns.background')),
                                ColorPicker::make('border')
                                    ->label(trans('filament-notes::messages.columns.border')),
                                ColorPicker::make('color')
                                    ->label(trans('filament-notes::messages.columns.color')),
                                Select::make('font_size')
                                    ->label(trans('filament-notes::messages.columns.font_size'))
                                    ->default('1em')
                                    ->searchable()
                                    ->options([
                                        '1em' => 'SM',
                                        '1.25em' => 'MD',
                                        '1.5em' => 'LG',
                                        '1.75em' => 'XL',
                                    ]),
                                Select::make('group')
                                    ->hidden(! filament('filament-notes')->useGroups)
                                    ->label(trans('filament-notes::messages.columns.group'))
                                    ->searchable()
                                    ->options(
                                        Type::query()
                                            ->where('for', 'notes')
                                            ->where('type', 'groups')
                                            ->pluck('name', 'key')
                                            ->toArray()
                                    ),
                                Select::make('status')
                                    ->hidden(! filament('filament-notes')->useStatus)
                                    ->label(trans('filament-notes::messages.columns.status'))
                                    ->searchable()
                                    ->options(
                                        Type::query()
                                            ->where('for', 'notes')
                                            ->where('type', 'status')
                                            ->pluck('name', 'key')
                                            ->toArray()
                                    ),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 3,
                            ]),

                    ])
                    ->contained(false)
                    ->columnSpanFull(),
            ]),
        ];
    }
}
