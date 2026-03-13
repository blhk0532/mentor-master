<?php

declare(strict_types=1);

namespace Livewire\Features\SupportFileUploads;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\WhitespacePathNormalizer;

class FileUploadConfiguration
{
    public static function storage()
    {
        $disk = self::disk();

        if (app()->runningUnitTests()) {
            // We want to "fake" the first time in a test run, but not again because
            // Storage::fake() wipes the storage directory every time its called.
            rescue(
                // If the storage disk is not found (meaning it's the first time),
                // this will throw an error and trip the second callback.
                fn () => Storage::disk($disk),
                fn () => Storage::fake($disk),
                // swallows the error that is thrown on the first try
                report: false
            );
        }

        return Storage::disk($disk);
    }

    public static function disk()
    {
        if (app()->runningUnitTests()) {
            return 'tmp-for-tests';
        }

        return config('livewire.temporary_file_upload.disk') ?: config('filesystems.default');
    }

    public static function diskConfig()
    {
        return config('filesystems.disks.'.self::disk());
    }

    public static function isUsingS3()
    {
        $diskBeforeTestFake = config('livewire.temporary_file_upload.disk') ?: config('filesystems.default');

        return config('filesystems.disks.'.mb_strtolower($diskBeforeTestFake).'.driver') === 's3';
    }

    public static function isUsingGCS()
    {
        $diskBeforeTestFake = config('livewire.temporary_file_upload.disk') ?: config('filesystems.default');

        return config('filesystems.disks.'.mb_strtolower($diskBeforeTestFake).'.driver') === 'gcs';
    }

    public static function normalizeRelativePath($path)
    {
        return (new WhitespacePathNormalizer)->normalizePath($path);
    }

    public static function directory()
    {
        return self::normalizeRelativePath(config('livewire.temporary_file_upload.directory') ?: 'livewire-tmp');
    }

    public static function path($path = '', $withS3Root = true)
    {
        $prefix = $withS3Root ? self::s3Root() : '';
        $directory = self::directory();
        $path = self::normalizeRelativePath($path);

        return $prefix.($prefix ? '/' : '').$directory.($path ? '/' : '').$path;
    }

    public static function mimeType($filename)
    {
        $mimeType = self::storage()->mimeType(self::path($filename));

        return $mimeType === 'image/svg' ? 'image/svg+xml' : $mimeType;
    }

    public static function lastModified($filename)
    {
        return self::storage()->lastModified($filename);
    }

    public static function middleware()
    {
        return config('livewire.temporary_file_upload.middleware') ?: 'throttle:60,1';
    }

    public static function shouldCleanupOldUploads()
    {
        return config('livewire.temporary_file_upload.cleanup', true);
    }

    public static function rules()
    {
        $rules = config('livewire.temporary_file_upload.rules');

        if (is_null($rules)) {
            return ['required', 'file', 'max:12288'];
        }

        if (is_array($rules)) {
            return $rules;
        }

        return explode('|', $rules);
    }

    public static function maxUploadTime()
    {
        return config('livewire.temporary_file_upload.max_upload_time') ?: 5;
    }

    public static function storeTemporaryFile($file, $disk)
    {
        $filename = TemporaryUploadedFile::generateHashName($file);
        $metaFilename = $filename.'.json';

        Storage::disk($disk)->put('/'.self::path($metaFilename), json_encode([
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'hash' => $file->hashName(),
        ]));

        return $file->storeAs('/'.self::path(), $filename, [
            'disk' => $disk,
        ]);
    }

    private static function s3Root()
    {
        if (! self::isUsingS3()) {
            return '';
        }

        $diskConfig = self::diskConfig();

        if (! is_array($diskConfig)) {
            return '';
        }

        $root = $diskConfig['root'] ?? null;

        return $root !== null ? self::normalizeRelativePath($root) : '';
    }
}
