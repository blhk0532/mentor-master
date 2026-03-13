<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // New table
        Schema::create('sweden_postorter', function (Blueprint $table) {
            $table->id();
            $table->string('post_ort');
            $table->string('kommun')->nullable();
            $table->string('lan')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('personer')->nullable();
            $table->integer('foretag')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sweden_postorter');
    }
};
