<?php

namespace Tallcms\Cms\Traits;

use App\Services\PluginLicenseService;

trait RequiresLicense
{
    /**
     * The plugin slug for license validation
     */
    protected static string $licensePluginSlug = 'tallcms/cms';

    /**
     * Check if the plugin has ever been licensed
     *
     * Once a license has been activated, the plugin works forever.
     * Expired licenses only lose access to updates, not functionality.
     * Watermark only shows for users who have NEVER activated a license.
     */
    protected static function hasEverBeenLicensed(): bool
    {
        try {
            $licenseService = app(PluginLicenseService::class);

            return $licenseService->hasEverBeenLicensed(static::$licensePluginSlug);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Wrap content with license watermark if never licensed
     *
     * Note: Watermark only shows for users who have NEVER had a license.
     * Once activated, even if expired, no watermark - they just can't update.
     */
    protected static function wrapWithLicenseCheck(string $content): string
    {
        if (static::hasEverBeenLicensed()) {
            return $content;
        }

        return static::addWatermark($content);
    }

    /**
     * Add watermark overlay to unlicensed content
     */
    protected static function addWatermark(string $content): string
    {
        $watermark = <<<'HTML'
<div class="tallcms-pro-unlicensed relative">
    <div class="absolute inset-0 bg-gray-900/5 dark:bg-gray-100/5 backdrop-blur-[1px] z-10 flex items-center justify-center pointer-events-none">
        <div class="bg-amber-100 dark:bg-amber-900/50 border border-amber-300 dark:border-amber-700 rounded-lg px-4 py-2 shadow-lg transform -rotate-2">
            <span class="text-amber-800 dark:text-amber-200 text-sm font-medium">
                TallCMS Pro - Unlicensed
            </span>
        </div>
    </div>
    <div class="opacity-75">
        CONTENT_PLACEHOLDER
    </div>
</div>
HTML;

        return str_replace('CONTENT_PLACEHOLDER', $content, $watermark);
    }
}
