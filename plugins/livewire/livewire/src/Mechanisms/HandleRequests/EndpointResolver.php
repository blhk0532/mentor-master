<?php

declare(strict_types=1);

namespace Livewire\Mechanisms\HandleRequests;

class EndpointResolver
{
    /**
     * Get the base path prefix for all Livewire endpoints.
     *
     * Uses APP_KEY to generate a unique prefix per installation,
     * making it harder to target Livewire apps with universal scanners.
     */
    public static function prefix(): string
    {
        $hash = mb_substr(hash('sha256', config('app.key').'livewire-endpoint'), 0, 8);

        return '/livewire-'.$hash;
    }

    /**
     * Get the path for the update endpoint.
     */
    public static function updatePath(): string
    {
        return self::prefix().'/update';
    }

    /**
     * Get the path for the JavaScript asset endpoint.
     */
    public static function scriptPath(bool $minified = false): string
    {
        $file = $minified ? 'livewire.min.js' : 'livewire.js';

        return self::prefix().'/'.$file;
    }

    /**
     * Get the path for the source map endpoint.
     */
    public static function mapPath(bool $csp = false): string
    {
        $file = $csp ? 'livewire.csp.min.js.map' : 'livewire.min.js.map';

        return self::prefix().'/'.$file;
    }

    /**
     * Get the path for the file upload endpoint.
     */
    public static function uploadPath(): string
    {
        return self::prefix().'/upload-file';
    }

    /**
     * Get the path for the file preview endpoint.
     */
    public static function previewPath(): string
    {
        return self::prefix().'/preview-file/{filename}';
    }

    /**
     * Get the path for component JavaScript modules.
     */
    public static function componentJsPath(): string
    {
        return self::prefix().'/js/{component}.js';
    }

    /**
     * Get the path for component CSS modules (scoped styles).
     */
    public static function componentCssPath(): string
    {
        return self::prefix().'/css/{component}.css';
    }

    /**
     * Get the path for component global CSS modules.
     */
    public static function componentGlobalCssPath(): string
    {
        return self::prefix().'/css/{component}.global.css';
    }
}
