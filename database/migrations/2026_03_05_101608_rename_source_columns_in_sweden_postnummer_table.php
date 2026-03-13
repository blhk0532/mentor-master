<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sweden_postnummer')) {
            return;
        }

        Schema::table('sweden_postnummer', function (Blueprint $table) {
            if (Schema::hasColumn('sweden_postnummer', 'source_created_at') && ! Schema::hasColumn('sweden_postnummer', 'personer_saved')) {
                $table->timestamp('personer_saved')->nullable()->after('foretag');
            }

            if (Schema::hasColumn('sweden_postnummer', 'source_updated_at') && ! Schema::hasColumn('sweden_postnummer', 'foretag_saved')) {
                $table->timestamp('foretag_saved')->nullable()->after('personer_saved');
            }
        });

        if (Schema::hasColumn('sweden_postnummer', 'source_created_at') && Schema::hasColumn('sweden_postnummer', 'personer_saved')) {
            DB::statement('UPDATE sweden_postnummer SET personer_saved = source_created_at WHERE personer_saved IS NULL AND source_created_at IS NOT NULL');
        }

        if (Schema::hasColumn('sweden_postnummer', 'source_updated_at') && Schema::hasColumn('sweden_postnummer', 'foretag_saved')) {
            DB::statement('UPDATE sweden_postnummer SET foretag_saved = source_updated_at WHERE foretag_saved IS NULL AND source_updated_at IS NOT NULL');
        }

        Schema::table('sweden_postnummer', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('sweden_postnummer', 'source_created_at')) {
                $dropColumns[] = 'source_created_at';
            }

            if (Schema::hasColumn('sweden_postnummer', 'source_updated_at')) {
                $dropColumns[] = 'source_updated_at';
            }

            if ($dropColumns !== []) {
                $table->dropColumn($dropColumns);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sweden_postnummer')) {
            return;
        }

        Schema::table('sweden_postnummer', function (Blueprint $table) {
            if (Schema::hasColumn('sweden_postnummer', 'personer_saved') && ! Schema::hasColumn('sweden_postnummer', 'source_created_at')) {
                $table->timestamp('source_created_at')->nullable()->after('foretag');
            }

            if (Schema::hasColumn('sweden_postnummer', 'foretag_saved') && ! Schema::hasColumn('sweden_postnummer', 'source_updated_at')) {
                $table->timestamp('source_updated_at')->nullable()->after('source_created_at');
            }
        });

        if (Schema::hasColumn('sweden_postnummer', 'personer_saved') && Schema::hasColumn('sweden_postnummer', 'source_created_at')) {
            DB::statement('UPDATE sweden_postnummer SET source_created_at = personer_saved WHERE source_created_at IS NULL AND personer_saved IS NOT NULL');
        }

        if (Schema::hasColumn('sweden_postnummer', 'foretag_saved') && Schema::hasColumn('sweden_postnummer', 'source_updated_at')) {
            DB::statement('UPDATE sweden_postnummer SET source_updated_at = foretag_saved WHERE source_updated_at IS NULL AND foretag_saved IS NOT NULL');
        }

        Schema::table('sweden_postnummer', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('sweden_postnummer', 'personer_saved')) {
                $dropColumns[] = 'personer_saved';
            }

            if (Schema::hasColumn('sweden_postnummer', 'foretag_saved')) {
                $dropColumns[] = 'foretag_saved';
            }

            if ($dropColumns !== []) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
