<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bestiary_entries', 'source_payload')) {
            Schema::table('bestiary_entries', function (Blueprint $table) {
                $table->longText('source_payload')->nullable()->after('last_synced_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bestiary_entries', 'source_payload')) {
            Schema::table('bestiary_entries', function (Blueprint $table) {
                $table->dropColumn('source_payload');
            });
        }
    }
};
