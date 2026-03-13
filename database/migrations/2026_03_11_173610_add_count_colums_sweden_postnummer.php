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
            $table->string('personer_merinfo_saved')->nullable()->after('personer_saved');

    }
 }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sweden_postnummer', function (Blueprint $table) {
            $table->dropColumn('personer_merinfo_saved');
            $table->dropColumn('personer_ratsit_saved');
            $table->dropColumn('personer_hitta_saved');
        });
    }
};
