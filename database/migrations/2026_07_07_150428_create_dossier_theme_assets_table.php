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
        Schema::create('dossier_theme_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_theme_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['parchment', 'frame', 'emblem', 'vignette']);
            $table->string('path');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_theme_assets');
    }
};
