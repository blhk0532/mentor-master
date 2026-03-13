<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sweden_postnummer', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('csv_id')->nullable()->index();
            $table->string('post_nummer')->unique();
            $table->string('post_ort')->nullable()->index();
            $table->string('lan')->nullable()->index();
            $table->string('kommun')->nullable()->index();
            $table->string('country', 2)->nullable()->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('personer')->nullable();
            $table->integer('foretag')->nullable();
            $table->timestamp('personer_saved')->nullable();
            $table->timestamp('foretag_saved')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sweden_postnummer');
    }
};
