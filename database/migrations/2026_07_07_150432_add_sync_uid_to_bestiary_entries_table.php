<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bestiary_entries', 'sync_uid')) {
            Schema::table('bestiary_entries', function (Blueprint $table) {
                $table->string('sync_uid', 120)->nullable()->after('slug');
                $table->timestamp('last_synced_at')->nullable()->after('published_at');
                $table->unique(['user_id', 'sync_uid'], 'bestiary_entries_user_sync_uid_unique');
            });
        }

        DB::table('bestiary_entries')
            ->whereNull('sync_uid')
            ->orderBy('id')
            ->select(['id', 'slug'])
            ->get()
            ->each(function ($entry) {
                DB::table('bestiary_entries')
                    ->where('id', $entry->id)
                    ->update([
                        'sync_uid' => $entry->slug ?: 'server-entry-'.$entry->id,
                        'last_synced_at' => now(),
                    ]);
            });
    }

    public function down(): void
    {
        if (Schema::hasColumn('bestiary_entries', 'sync_uid')) {
            Schema::table('bestiary_entries', function (Blueprint $table) {
                $table->dropUnique('bestiary_entries_user_sync_uid_unique');
                $table->dropColumn(['sync_uid', 'last_synced_at']);
            });
        }
    }
};
