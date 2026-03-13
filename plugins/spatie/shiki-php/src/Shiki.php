<?php

declare(strict_types=1);

namespace Spatie\ShikiPhp;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class Shiki
{
    /**
     * @var string|array<string, string> Can be a single theme or an array with a light and a dark theme.
     */
    private mixed $defaultTheme;

    private static ?string $customWorkingDirPath = null;

    /**
     * @param  string|array<string, string>  $defaultTheme  Can be a single theme or an array with a light and a dark theme.
     */
    public function __construct(mixed $defaultTheme = 'nord')
    {
        $this->defaultTheme = $defaultTheme;
    }

    public static function setCustomWorkingDirPath(?string $path)
    {
        self::$customWorkingDirPath = $path;
    }

    /**
     * @param  string|array<string, string>|null  $theme  Can be a single theme or an array with a light and a dark theme.
     */
    public static function highlight(
        string $code,
        ?string $language = null,
        mixed $theme = null,
        ?array $highlightLines = null,
        ?array $addLines = null,
        ?array $deleteLines = null,
        ?array $focusLines = null
    ): string {
        $language = $language ?? 'php';
        $theme = $theme ?? 'nord';

        return (new self)->highlightCode($code, $language, $theme, [
            'highlightLines' => $highlightLines ?? [],
            'addLines' => $addLines ?? [],
            'deleteLines' => $deleteLines ?? [],
            'focusLines' => $focusLines ?? [],
        ]);
    }

    public function getAvailableLanguages(): array
    {
        $shikiResult = $this->callShiki('languages');

        $languages = json_decode($shikiResult, true);

        sort($languages);

        return $languages;
    }

    public function getAvailableThemes(): array
    {
        $shikiResult = $this->callShiki('themes');

        return json_decode($shikiResult, true);
    }

    public function languageIsAvailable(string $language): bool
    {
        $shikiResult = $this->callShiki('aliases');

        $aliases = json_decode($shikiResult, true);

        return in_array($language, $aliases);
    }

    public function themeIsAvailable(string $theme): bool
    {
        return in_array($theme, $this->getAvailableThemes());
    }

    public function highlightCode(string $code, string $language, mixed $theme = null, ?array $options = []): string
    {
        $theme = $theme ?? $this->defaultTheme;

        return $this->callShiki($code, $language, $theme, $options);
    }

    public function getWorkingDirPath(): string
    {
        if (self::$customWorkingDirPath !== null && ($path = realpath(self::$customWorkingDirPath)) !== false) {
            return $path;
        }

        return realpath(dirname(__DIR__).'/bin');
    }

    private function callShiki(...$arguments): string
    {
        $home = getenv('HOME');
        $command = [
            (new ExecutableFinder)->find('node', 'node', [
                '/usr/local/bin',
                '/opt/homebrew/bin',
                $home.'/n/bin', // support https://github.com/tj/n
            ]),
            'shiki.js',
            json_encode(array_values($arguments)),
        ];

        $process = new Process(
            $command,
            $this->getWorkingDirPath(),
            null,
        );

        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
