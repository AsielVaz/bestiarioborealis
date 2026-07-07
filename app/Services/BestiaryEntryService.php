<?php

namespace App\Services;

use App\Models\BestiaryEntry;
use App\Models\DossierTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BestiaryEntryService
{
    private const RELATIONS = [
        'subtitles' => ['model' => 'subtitles', 'fields' => ['value']],
        'affinities' => ['model' => 'affinities', 'fields' => ['value']],
        'habitats' => ['model' => 'habitats', 'fields' => ['value']],
        'behaviors' => ['model' => 'behaviors', 'fields' => ['value']],
        'abilities' => ['model' => 'abilities', 'fields' => ['name', 'description']],
        'techniques' => ['model' => 'techniques', 'fields' => ['name', 'description']],
        'weaknesses' => ['model' => 'weaknesses', 'fields' => ['description']],
        'loot' => ['model' => 'loot', 'fields' => ['name', 'description', 'rarity']],
        'stats' => ['model' => 'stats', 'fields' => ['name', 'value', 'value_label']],
        'vignettes' => ['model' => 'vignettes', 'fields' => ['title', 'description', 'image_path']],
        'scholar_notes' => ['model' => 'scholarNotes', 'fields' => ['note']],
    ];

    public function createFromRequest(Request $request): BestiaryEntry
    {
        return $this->persist($request->validated(), new BestiaryEntry(), $request->user()?->id);
    }

    public function updateFromRequest(Request $request, BestiaryEntry $entry): BestiaryEntry
    {
        return $this->persist($request->validated(), $entry, $entry->user_id ?: $request->user()?->id);
    }

    public function createFromJson(array $payload, ?int $userId = null): BestiaryEntry
    {
        $sourcePayload = $payload;
        $payload = $this->normalizePayload($payload);
        $payload['source_payload'] = $sourcePayload;

        return $this->persist($payload, new BestiaryEntry(), $userId);
    }

    public function exportToJson(BestiaryEntry $entry): array
    {
        $entry->load([
            'dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors',
            'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes',
        ]);

        return [
            'title' => $entry->title,
            'account_id' => $entry->user_id,
            'creature_uid' => $entry->sync_uid,
            'server_id' => $entry->id,
            'slug' => $entry->slug,
            'classification' => $entry->classification,
            'category' => $entry->category,
            'threat_level' => $entry->threat_level,
            'height' => $entry->height,
            'description' => $entry->description,
            'last_record' => $entry->last_record,
            'status' => $entry->status,
            'final_combat_scenario' => $entry->final_combat_scenario,
            'theme_key' => $entry->dossierTheme?->key,
            'published_at' => $entry->published_at?->toISOString(),
            'updated_at' => $entry->updated_at?->toISOString(),
            'last_synced_at' => $entry->last_synced_at?->toISOString(),
            'source_payload' => $entry->source_payload,
            'origin' => $entry->origin?->only(['universe', 'game', 'campaign', 'source', 'region']),
            'subtitles' => $entry->subtitles->pluck('value')->values(),
            'affinities' => $entry->affinities->pluck('value')->values(),
            'habitats' => $entry->habitats->pluck('value')->values(),
            'behaviors' => $entry->behaviors->pluck('value')->values(),
            'abilities' => $entry->abilities->map->only(['name', 'description'])->values(),
            'techniques' => $entry->techniques->map->only(['name', 'description'])->values(),
            'weaknesses' => $entry->weaknesses->map->only(['description'])->values(),
            'loot' => $entry->loot->map->only(['name', 'description', 'rarity'])->values(),
            'stats' => $entry->stats->map->only(['name', 'value', 'value_label'])->values(),
            'vignettes' => $entry->vignettes->map->only(['title', 'description'])->values(),
            'scholar_notes' => $entry->scholarNotes->map->only(['note'])->values(),
        ];
    }

    private function persist(array $payload, BestiaryEntry $entry, ?int $userId): BestiaryEntry
    {
        return DB::transaction(function () use ($payload, $entry, $userId) {
            $themeId = $payload['dossier_theme_id'] ?? null;
            if (! $themeId && ! empty($payload['theme_key'])) {
                $themeId = DossierTheme::where('key', $payload['theme_key'])->value('id');
            }

            $entry->fill(Arr::only($payload, [
                'title', 'slug', 'sync_uid', 'classification', 'category', 'threat_level', 'height',
                'description', 'last_record', 'status', 'final_combat_scenario',
                'main_image_path', 'primary_color', 'accent_color', 'parchment_tone',
                'frame_style', 'published_at', 'source_payload',
            ]));

            $entry->slug = $entry->slug ?: $this->uniqueSlug($payload['title'], $entry->id);
            $entry->sync_uid = $entry->sync_uid ?: ($payload['creature_uid'] ?? Str::uuid()->toString());
            $entry->user_id = $entry->user_id ?: $userId;
            $entry->dossier_theme_id = $themeId;
            $entry->last_synced_at = now();
            $entry->save();

            $entry->origin()->updateOrCreate([], Arr::only($payload['origin'] ?? [], [
                'universe', 'game', 'campaign', 'source', 'region',
            ]));

            foreach (self::RELATIONS as $inputKey => $definition) {
                $relation = $definition['model'];
                $entry->{$relation}()->delete();

                foreach (array_values($payload[$inputKey] ?? []) as $index => $item) {
                    $row = is_array($item) ? Arr::only($item, $definition['fields']) : [$definition['fields'][0] => $item];
                    $row['sort_order'] = (int) ($item['sort_order'] ?? $index);

                    if (array_filter($row, fn ($value) => $value !== null && $value !== '')) {
                        $entry->{$relation}()->create($row);
                    }
                }
            }

            return $entry->fresh();
        });
    }

    public function upsertForSync(array $payload, int $userId, string $creatureUid): BestiaryEntry
    {
        $sourcePayload = $payload;
        $entry = BestiaryEntry::where('user_id', $userId)
            ->where('sync_uid', $creatureUid)
            ->first() ?? new BestiaryEntry();

        $payload = $this->normalizePayload($payload);
        $payload['sync_uid'] = $creatureUid;
        $payload['source_payload'] = $sourcePayload;

        return $this->persist($payload, $entry, $userId);
    }

    private function normalizePayload(array $payload): array
    {
        if (isset($payload['entry']) && is_array($payload['entry'])) {
            $payload = $payload['entry'];
        }

        $payload['title'] = strip_tags((string) ($payload['title'] ?? 'Criatura sin nombre'));
        $payload['classification'] = strip_tags((string) ($payload['classification'] ?? 'Entidad'));
        $payload['threat_level'] = strip_tags((string) ($payload['threat_level'] ?? 'Desconocida'));
        $payload['description'] = strip_tags((string) ($payload['description'] ?? 'Sin descripción registrada.'));

        return $payload;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'entrada';
        $slug = $base;
        $i = 2;

        while (BestiaryEntry::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
