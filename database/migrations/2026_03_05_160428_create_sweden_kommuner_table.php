<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // New table
        Schema::create('sweden_kommuner', function (Blueprint $table) {
            $table->id();
            $table->string('kommun')->unique();
            $table->string('lan')->index();
            $table->integer('personer')->nullable();
            $table->integer('foretag')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{{ table }}');
    }
};
