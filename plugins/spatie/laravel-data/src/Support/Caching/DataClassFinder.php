<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Support\Caching;

use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Dto;
use Spatie\LaravelData\Resource;
use Spatie\StructureDiscoverer\Discover;

class DataClassFinder
{
    /**
     * @param  array<string>  $directories
     */
    public function __construct(
        private array $directories,
        private bool $useReflection,
        private ?string $reflectionBasePath,
        private ?string $reflectionRootNamespace,
    ) {}

    public static function fromConfig(array $config): self
    {
        return new self(
            directories: array_filter($config['directories'], 'is_dir'),
            useReflection: $config['reflection_discovery']['enabled'],
            reflectionBasePath: $config['reflection_discovery']['base_path'],
            reflectionRootNamespace: $config['reflection_discovery']['root_namespace'],
        );
    }

    public function classes(): array
    {
        $discoverer = Discover::in(__DIR__.'/../../', ...$this->directories)
            ->implementing(BaseData::class);

        if ($this->useReflection) {
            $discoverer->useReflection($this->reflectionBasePath, $this->reflectionRootNamespace);
        }

        $classesToIgnore = [
            Dto::class,
            Data::class,
            Resource::class,
        ];

        $classes = array_filter(
            $discoverer->get(),
            fn (string $class) => ! in_array($class, $classesToIgnore)
        );

        return array_values($classes);
    }
}
