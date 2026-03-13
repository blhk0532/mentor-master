<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

it('runs plugin migrations and creates expected tables and columns', function () {
    // Run the plugin migrations
    $exit = Artisan::call('migrate', [
        '--path' => 'plugins/filament-user/database/migrations',
        '--realpath' => false,
        '--force' => true,
    ]);

    expect($exit)->toBe(0);

    expect(Schema::hasTable('user_types'))->toBeTrue();
    expect(Schema::hasTable('user_settings'))->toBeTrue();
    expect(Schema::hasTable('user_stats'))->toBeTrue();

    // users table got new columns
    expect(Schema::hasColumn('users', 'role'))->toBeTrue();
    expect(Schema::hasColumn('users', 'type_id'))->toBeTrue();
    expect(Schema::hasColumn('users', 'phone'))->toBeTrue();
    expect(Schema::hasColumn('users', 'team'))->toBeTrue();

    // Rollback plugin migrations
    Artisan::call('migrate:rollback', [
        '--path' => 'plugins/filament-user/database/migrations',
        '--force' => true,
    ]);
});
