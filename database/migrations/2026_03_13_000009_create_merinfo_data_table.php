<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merinfo_data', function (Blueprint $table): void {
            $table->id();
            $table->text('personnamn')->nullable();
            $table->string('givenNameOrFirstName')->nullable();
            $table->string('personalNumber')->nullable();
            $table->text('alder')->nullable();
            $table->text('kon')->nullable();
            $table->text('gatuadress')->nullable();
            $table->text('postnummer')->nullable();
            $table->text('postort')->nullable();
            $table->text('telefon')->nullable();
            $table->longText('telefonnummer')->nullable();
            $table->longText('telefoner')->nullable();
            $table->text('karta')->nullable();
            $table->text('link')->nullable();
            $table->text('bostadstyp')->nullable();
            $table->text('bostadspris')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_telefon')->default(false);
            $table->boolean('is_ratsit')->default(false);
            $table->boolean('is_hus')->default(false);
            $table->integer('merinfo_personer_total')->nullable();
            $table->integer('merinfo_foretag_total')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('merinfo_personer_count')->nullable()->default(0);
            $table->integer('merinfo_personer_queue')->nullable()->default(0);
        });

     //   DB::statement('CREATE UNIQUE INDEX merinfo_person_gata_unique ON merinfo_data (personnamn(191), gatuadress(191))');
    }

    public function down(): void
    {
        Schema::dropIfExists('merinfo_data');
    }
};
