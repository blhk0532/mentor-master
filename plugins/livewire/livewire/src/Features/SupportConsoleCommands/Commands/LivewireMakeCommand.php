<?php

declare(strict_types=1);

namespace Livewire\Features\SupportConsoleCommands\Commands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'livewire:make')]
class LivewireMakeCommand extends MakeCommand
{
    protected $name = 'livewire:make';

    protected $hidden = true; // Hide from command list to avoid duplication
}
