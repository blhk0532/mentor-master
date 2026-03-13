<?php

namespace TomatoPHP\ConsoleHelpers\Traits;

use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

trait RunCommand
{
    /**
     * Get the path to the appropriate PHP binary.
     */
    protected function phpBinary(): string
    {
        return (new PhpExecutableFinder)->find(false) ?: 'php';
    }

    public function phpCommand(array $commands, ?bool $useOutput = false): void
    {
        (new Process(array_merge([$this->phpBinary()], $commands), base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) use ($useOutput) {
                if ($useOutput) {
                    $this->output->write($output);
                }
            });
    }

    public function yarnCommand(array $commands, ?bool $withOutput = false): void
    {
        (new Process(array_merge([config('console-helpers.yarn-path')], $commands), base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) use ($withOutput) {
                if ($withOutput) {
                    $this->output->write($output);
                }
            });
    }

    public function artisanCommand(array $command, ?bool $withOutput = false): void
    {
        $this->phpCommand(array_merge(['artisan'], $command), $withOutput);
    }

    /**
     * Installs the given Composer Packages into the application.
     */
    protected function requireComposerPackages(mixed $packages, ?bool $withOutput = false): void
    {
        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) use ($withOutput) {
                if ($withOutput) {
                    $this->output->write($output);
                }
            });
    }

    /**
     * Install the given Composer Packages as "dev" dependencies.
     */
    protected function requireComposerDevPackages(mixed $packages, ?bool $withOutput = false): void
    {

        $command = array_merge(
            $command ?? ['composer', 'require', '--dev'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) use ($withOutput) {
                if ($withOutput) {
                    $this->output->write($output);
                }
            });
    }
}
