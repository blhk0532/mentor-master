<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sweden_postorter')) {
            return;
        }

        $duplicateGroups = DB::table('sweden_postorter')
            ->select('post_ort', DB::raw('MIN(id) as keep_id'))
            ->groupBy('post_ort')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicateGroups as $group) {
            DB::table('sweden_postorter')
                ->where('post_ort', $group->post_ort)
                ->where('id', '!=', $group->keep_id)
                ->delete();
        }

        Schema::table('sweden_postorter', function (Blueprint $table) {
            $table->unique('post_ort', 'sweden_postorter_post_ort_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sweden_postorter')) {
            return;
        }

        Schema::table('sweden_postorter', function (Blueprint $table) {
            $table->dropUnique('sweden_postorter_post_ort_unique');
        });
    }
};
