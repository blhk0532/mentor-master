<?php

declare(strict_types=1);

namespace Spatie\MediaLibrary\ResponsiveImages;

use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory;

class ResponsiveImage
{
    public function __construct(public string $fileName, public Media $media) {}

    public static function register(Media $media, $fileName, $conversionName): void
    {
        $responsiveImages = $media->responsive_images;

        $responsiveImages[$conversionName]['urls'][] = $fileName;

        $media->responsive_images = $responsiveImages;

        $media->save();
    }

    public static function registerTinySvg(Media $media, string $base64Svg, string $conversionName): void
    {
        $responsiveImages = $media->responsive_images;

        $responsiveImages[$conversionName]['base64svg'] = $base64Svg;

        $media->responsive_images = $responsiveImages;

        $media->save();
    }

    public function url(): string
    {
        $conversionName = '';

        if ($this->generatedFor() !== 'media_library_original') {
            $conversionName = $this->generatedFor();
        }

        $urlGenerator = UrlGeneratorFactory::createForMedia($this->media, $conversionName);

        $url = $urlGenerator->getResponsiveImagesDirectoryUrl().rawurlencode($this->fileName);

        if (config('media-library.version_urls') === true) {
            $url = "{$url}?v={$this->media->updated_at->timestamp}";
        }

        return $url;
    }

    public function generatedFor(): string
    {
        $propertyParts = $this->getPropertyParts();

        array_pop($propertyParts);

        array_pop($propertyParts);

        return implode('_', $propertyParts);
    }

    public function width(): int
    {
        $propertyParts = $this->getPropertyParts();

        array_pop($propertyParts);

        return (int) last($propertyParts);
    }

    public function height(): int
    {
        $propertyParts = $this->getPropertyParts();

        return (int) last($propertyParts);
    }

    /**
     * @return $this
     */
    public function delete(): self
    {
        $pathGenerator = PathGeneratorFactory::create($this->media);

        $path = $pathGenerator->getPathForResponsiveImages($this->media);

        $fullPath = $path.$this->fileName;

        app(Filesystem::class)->removeFile($this->media, $fullPath);

        $responsiveImages = $this->media->responsive_images;

        unset($responsiveImages[$this->generatedFor()]);

        $this->media->responsive_images = $responsiveImages;

        $this->media->save();

        return $this;
    }

    private function getPropertyParts(): array
    {
        $propertyString = $this->stringBetween($this->fileName, '___', '.');

        return explode('_', $propertyString);
    }

    private function stringBetween(string $subject, string $startCharacter, string $endCharacter): string
    {
        $lastPos = mb_strrpos($subject, $startCharacter);

        $between = mb_substr($subject, $lastPos);

        $between = str_replace('___', '', $between);

        $between = mb_strstr($between, $endCharacter, true);

        return $between;
    }
}
