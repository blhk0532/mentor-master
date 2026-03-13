<?php

namespace TomatoPHP\ConsoleHelpers\Traits;

use Illuminate\Support\Facades\File;

trait HandleModules
{
    /**
     * @throws \JsonException
     */
    public function activeModule(string $module, bool $stop = false): void
    {
        $check = File::exists(base_path('/modules_statuses.json'));
        if ($check) {
            $fileJson = @json_decode(File::get(base_path('/modules_statuses.json')), false, 512, JSON_THROW_ON_ERROR);
            $fileJson->{$module} = $stop ?: true;
            File::put(base_path('/modules_statuses.json'), @json_encode($fileJson, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * @throws \JsonException
     */
    public function stopAllModules(): void
    {
        $check = File::exists(base_path('/modules_statuses.json'));
        if ($check) {
            $fileJson = @json_decode(File::get(base_path('/modules_statuses.json')), false, 512, JSON_THROW_ON_ERROR);
            foreach ($fileJson as $item) {
                $item = false;
            }
            File::put(base_path('/modules_statuses.json'), @json_encode($fileJson, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * @throws \JsonException
     */
    public function activeAllModules(): void
    {
        $check = File::exists(base_path('/modules_statuses.json'));
        if ($check) {
            $fileJson = @json_decode(File::get(base_path('/modules_statuses.json')), false, 512, JSON_THROW_ON_ERROR);
            foreach ($fileJson as $item) {
                $item = true;
            }
            File::put(base_path('/modules_statuses.json'), @json_encode($fileJson, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}
