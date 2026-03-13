<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('email');
            $table->foreignId('type_id')->nullable()->constrained('user_types')->nullOnDelete()->after('role');
            $table->string('phone')->nullable()->after('type_id');
            $table->string('team')->nullable()->after('phone');
            $table->text('two_factor_secret')->after('password')->nullable();
            $table->text('two_factor_recovery_codes')->after('two_factor_secret')->nullable();
            $table->timestamp('two_factor_confirmed_at')->after('two_factor_recovery_codes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn([
                'role',
                'type_id',
                'phone',
                'team',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at', ]);
        });
    }
};
