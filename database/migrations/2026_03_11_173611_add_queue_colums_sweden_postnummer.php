<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sweden_postnummer', function (Blueprint $table) {
            $table->integer('personer_merinfo_queue')->nullable()->default(0)->after('personer_hitta_saved');
            $table->integer('personer_ratsit_queue')->nullable()->default(0)->after('personer_merinfo_queue');
            $table->integer('personer_hitta_queue')->nullable()->default(0)->after('personer_ratsit_queue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sweden_postnummer', function (Blueprint $table) {
            $table->dropColumn('personer_merinfo_queue');
            $table->dropColumn('personer_ratsit_queue');
            $table->dropColumn('personer_hitta_queue');
        });
    }
};
