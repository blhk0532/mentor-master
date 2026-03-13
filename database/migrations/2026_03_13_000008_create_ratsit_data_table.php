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
        Schema::create('ratsit_data', function (Blueprint $table): void {
            $table->id();
            $table->text('gatuadress')->nullable();
            $table->text('postnummer')->nullable();
            $table->text('postort')->nullable();
            $table->text('forsamling')->nullable();
            $table->text('kommun')->nullable();
            $table->text('lan')->nullable();
            $table->text('adressandring')->nullable();
            $table->longText('telfonnummer')->nullable();
            $table->text('stjarntacken')->nullable();
            $table->text('fodelsedag')->nullable();
            $table->text('personnummer')->nullable();
            $table->text('alder')->nullable();
            $table->text('kon')->nullable();
            $table->text('civilstand')->nullable();
            $table->text('fornamn')->nullable();
            $table->text('efternamn')->nullable();
            $table->text('personnamn')->nullable();
            $table->text('telefon')->nullable();
            $table->longText('epost_adress')->nullable();
            $table->text('agandeform')->nullable();
            $table->text('bostadstyp')->nullable();
            $table->text('boarea')->nullable();
            $table->text('byggar')->nullable();
            $table->text('fastighet')->nullable();
            $table->longText('personer')->nullable();
            $table->longText('foretag')->nullable();
            $table->longText('grannar')->nullable();
            $table->longText('fordon')->nullable();
            $table->longText('hundar')->nullable();
            $table->longText('bolagsengagemang')->nullable();
            $table->text('longitude')->nullable();
            $table->text('latitud')->nullable();
            $table->text('google_maps')->nullable();
            $table->text('google_streetview')->nullable();
            $table->text('ratsit_se')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_hus')->default(false);
            $table->boolean('is_telefon')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->text('kommun_ratsit')->nullable();
            $table->boolean('is_queued')->default(false);
        });

    //    DB::statement('CREATE INDEX ratsit_data_postnummer_index ON ratsit_data (postnummer(191))');
    //    DB::statement('CREATE INDEX ratsit_data_postort_index ON ratsit_data (postort(191))');
    //    DB::statement('CREATE INDEX ratsit_data_kommun_index ON ratsit_data (kommun(191))');
    //    DB::statement('CREATE INDEX ratsit_data_lan_index ON ratsit_data (lan(191))');
    //    DB::statement('CREATE INDEX ratsit_data_agandeform_index ON ratsit_data (agandeform(191))');
    //    DB::statement('CREATE INDEX ratsit_data_bostadstyp_index ON ratsit_data (bostadstyp(191))');
    //    DB::statement('CREATE INDEX ratsit_data_created_at_index ON ratsit_data (created_at)');
    //    DB::statement('CREATE UNIQUE INDEX unique_gatuadress_personnamn ON ratsit_data (gatuadress(191), personnamn(191))');
    }

    public function down(): void
    {
        Schema::dropIfExists('ratsit_data');
    }
};
