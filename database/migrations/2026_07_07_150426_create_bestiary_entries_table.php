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
        Schema::create('bestiary_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('dossier_theme_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('classification');
            $table->string('category')->nullable();
            $table->string('threat_level');
            $table->string('height')->nullable();
            $table->text('description');
            $table->text('last_record')->nullable();
            $table->string('status')->nullable();
            $table->text('final_combat_scenario')->nullable();
            $table->string('main_image_path')->nullable();
            $table->string('primary_color', 20)->nullable();
            $table->string('accent_color', 20)->nullable();
            $table->string('parchment_tone', 20)->nullable();
            $table->string('frame_style')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['classification', 'threat_level', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bestiary_entries');
    }
};
