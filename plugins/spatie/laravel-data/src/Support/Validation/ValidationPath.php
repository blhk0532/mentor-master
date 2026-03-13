<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Validation;

use Stringable;

class ValidationPath implements Stringable
{
    public function __construct(
        private readonly array $path = []
    ) {}

    public function __toString()
    {
        return $this->get();
    }

    public static function create(?string $path = null): self
    {
        if ($path === null) {
            return new self;
        }

        return new self(explode('.', $path));
    }

    public function property(string $property): self
    {
        $newPath = $this->path;

        $newPath[] = $property;

        return new self($newPath);
    }

    public function isRoot(): bool
    {
        return empty($this->path);
    }

    public function equals(string|self $other): bool
    {
        $otherPath = $other instanceof self
            ? $other->path
            : explode('.', $other);

        return $this->path === $otherPath;
    }

    public function get(): ?string
    {
        return implode('.', $this->path);
    }
}
