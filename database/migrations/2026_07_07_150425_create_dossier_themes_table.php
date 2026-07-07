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
        Schema::create('dossier_themes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('primary_color', 20);
            $table->string('accent_color', 20);
            $table->string('stat_color', 20)->nullable();
            $table->string('seal_color', 20)->nullable();
            $table->string('parchment_tone', 20);
            $table->string('ink_color', 20)->nullable();
            $table->string('muted_ink', 20)->nullable();
            $table->string('frame_style')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_themes');
    }
};
