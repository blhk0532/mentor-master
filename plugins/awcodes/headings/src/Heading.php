<?php

declare(strict_types=1);

namespace Awcodes\Headings;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Support\Colors\Color;
use Filament\Support\Concerns\HasColor;

class Heading extends Component
{
    use HasColor;

    protected string|int $level = 2;

    protected string|Closure $content = '';

    protected string $view = 'headings::heading';

    public function __construct(string|int $level)
    {
        $this->level($level);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(false);
    }

    public static function make(string|int $level): static
    {
        return app(self::class, ['level' => $level]);
    }

    public function content(string|Closure $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function level(string|int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getColor(): array
    {
        return $this->evaluate($this->color) ?? Color::Amber;
    }

    public function getContent(): string
    {
        return (string) $this->evaluate($this->content);
    }

    public function getLevel(): string
    {
        return is_int($this->level) ? 'h'.$this->level : $this->level;
    }
}
