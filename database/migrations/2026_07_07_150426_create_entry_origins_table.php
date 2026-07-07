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
        Schema::create('entry_origins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bestiary_entry_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('universe')->nullable();
            $table->string('game')->nullable();
            $table->string('campaign')->nullable();
            $table->string('source')->nullable();
            $table->string('region')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_origins');
    }
};
