<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use MWGuerra\WebTerminal\Filament\Pages\Terminal as BaseTerminal;
use MWGuerra\WebTerminal\Schemas\Components\WebTerminal;
use UnitEnum;

class Terminals extends BaseTerminal
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $navigationLabel = 'Terminals x4';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 101;

    protected static ?string $slug = 'terminals';

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        $this->createTerminalSection('terminal-1', ' '),
                        $this->createTerminalSection('terminal-2', ' '),
                        $this->createTerminalSection('terminal-3', ' '),
                        $this->createTerminalSection('terminal-4', ' '),
                    ]),
            ]);
    }

    protected function createTerminalSection(string $key, string $title): Section
    {
        FilamentAsset::register([
            Css::make('custom', __DIR__.'/../../resources/css/custom.css'),
        ]);

        return Section::make($title)
            ->schema([
                WebTerminal::make()
                    ->key($key)
                    ->local()
                    ->allowAllCommands()
                    ->workingDirectory(base_path())
                    ->timeout(55555)
                    ->prompt('$ ')
                    ->historyLimit(50)
                    ->height('420px')
                    ->title($title)
                    ->windowControls(true)
                    ->startConnected(true)
                    ->log(
                        enabled: true,
                        connections: true,
                        commands: true,
                        output: true,
                        identifier: $key,
                    ),
            ]);
    }
}
