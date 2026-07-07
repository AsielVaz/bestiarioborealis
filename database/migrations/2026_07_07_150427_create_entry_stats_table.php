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
        Schema::create('entry_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bestiary_entry_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('value');
            $table->string('value_label')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_stats');
    }
};
