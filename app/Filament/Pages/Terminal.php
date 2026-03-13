<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MWGuerra\WebTerminal\Filament\Pages\Terminal as BaseTerminal;
use MWGuerra\WebTerminal\Schemas\Components\WebTerminal;
use UnitEnum;

class Terminal extends BaseTerminal
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $navigationLabel = 'Terminal';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 100;

    protected static ?string $slug = 'terminal';

    /**
     * Customize the terminal schema.
     *
     * @see https://github.com/mwguerra/web-terminal for all available options
     */
    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('web-terminal::terminal.pages.terminal.local_terminal'))
                    ->description(__('web-terminal::terminal.pages.terminal.local_terminal_description'))
                    ->icon('heroicon-o-command-line')
                    ->schema([
                        WebTerminal::make()
                            ->key('app-terminal')
                            ->local()
                            ->allowAllCommands()
                            // WARNING: This allows all commands - use with caution
                            ->workingDirectory(base_path())
                            ->timeout(55555)
                            ->prompt('$⍰')
                            ->historyLimit(50)
                            ->height('400px')
                            ->title(__('web-terminal::terminal.pages.terminal.local_terminal'))
                            ->windowControls(true)
                            ->startConnected(true)
                            ->log(
                                enabled: true,
                                connections: true,
                                commands: true,
                                output: true,
                                identifier: 'app-terminal',
                            ),
                    ]),
            ]);
    }
}
