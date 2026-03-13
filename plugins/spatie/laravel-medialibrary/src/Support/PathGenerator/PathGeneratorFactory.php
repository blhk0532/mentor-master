<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\Support\PathGenerator;

use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidPathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PathGeneratorFactory
{
    private static array $customPathGenerators = [];

    public static function create(Media $media): PathGenerator
    {
        $pathGeneratorClass = self::getPathGeneratorClass($media);

        self::guardAgainstInvalidPathGenerator($pathGeneratorClass);

        return app($pathGeneratorClass);
    }

    /**
     * @throws InvalidPathGenerator
     */
    public static function setCustomPathGenerators(string $model, string $pathGeneratorClass): void
    {
        self::guardAgainstInvalidPathGenerator($pathGeneratorClass);

        self::$customPathGenerators[$model] = $pathGeneratorClass;
    }

    private static function getPathGeneratorClass(Media $media)
    {
        $defaultPathGeneratorClass = config('media-library.path_generator');

        foreach (array_merge(config('media-library.custom_path_generators', []), self::$customPathGenerators) as $modelClass => $customPathGeneratorClass) {
            if (self::mediaBelongToModelClass($media, $modelClass)) {
                return $customPathGeneratorClass;
            }
        }

        return $defaultPathGeneratorClass;
    }

    private static function mediaBelongToModelClass(Media $media, string $modelClass): bool
    {
        // model doesn't have morphMap, so morph type and class are equal
        if (is_a($media->model_type, $modelClass, true)) {
            return true;
        }
        // config is set via morphMap alias
        if ($media->model_type === $modelClass) {
            return true;
        }
        // config is set via morphMap class name
        if (is_a((string) Relation::getMorphedModel($media->model_type), $modelClass, true)) {
            return true;
        }

        return false;
    }

    private static function guardAgainstInvalidPathGenerator(string $pathGeneratorClass): void
    {
        if (! class_exists($pathGeneratorClass)) {
            throw InvalidPathGenerator::doesntExist($pathGeneratorClass);
        }

        if (! is_subclass_of($pathGeneratorClass, PathGenerator::class)) {
            throw InvalidPathGenerator::doesNotImplementPathGenerator($pathGeneratorClass);
        }
    }
}
