<?php

declare(strict_types=1);

namespace Spatie\LaravelSettings\Migrations;

use Illuminate\Database\Migrations\Migration;

abstract class SettingsMigration extends Migration
{
    protected SettingsMigrator $migrator;

    public function __construct()
    {
        $this->migrator = app(SettingsMigrator::class);
    }

    abstract public function up();
}
