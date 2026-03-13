<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sweden_postorter')) {
            return;
        }

        Schema::table('sweden_postorter', function (Blueprint $table) {
            if (! Schema::hasIndex('sweden_postorter', 'sweden_postorter_kommun_index')) {
                $table->index('kommun', 'sweden_postorter_kommun_index');
            }

            if (! Schema::hasIndex('sweden_postorter', 'sweden_postorter_lan_index')) {
                $table->index('lan', 'sweden_postorter_lan_index');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sweden_postorter')) {
            return;
        }

        Schema::table('sweden_postorter', function (Blueprint $table) {
            if (Schema::hasIndex('sweden_postorter', 'sweden_postorter_kommun_index')) {
                $table->dropIndex('sweden_postorter_kommun_index');
            }

            if (Schema::hasIndex('sweden_postorter', 'sweden_postorter_lan_index')) {
                $table->dropIndex('sweden_postorter_lan_index');
            }
        });
    }
};
