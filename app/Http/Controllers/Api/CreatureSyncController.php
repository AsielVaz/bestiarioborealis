<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BestiaryEntry;
use App\Services\BestiaryEntryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreatureSyncController extends Controller
{
    public function __construct(private readonly BestiaryEntryService $entries)
    {
    }

    public function index(Request $request)
    {
        $accountId = $this->validatedAccountId($request);

        $query = BestiaryEntry::where('user_id', $accountId)
            ->with(['dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes'])
            ->orderBy('updated_at');

        if ($request->filled('since')) {
            $query->where('updated_at', '>', $request->date('since'));
        }

        return response()->json([
            'account_id' => $accountId,
            'server_time' => now()->toISOString(),
            'entries' => $query->get()->map(fn (BestiaryEntry $entry) => $this->entries->exportToJson($entry))->values(),
        ]);
    }

    public function show(Request $request, string $creatureUid)
    {
        $accountId = $this->validatedAccountId($request);
        $entry = $this->findEntry($accountId, $creatureUid);

        abort_unless($entry, 404, 'La creatura no existe en esta cuenta.');

        return response()->json([
            'exists' => true,
            'entry' => $this->entries->exportToJson($entry),
        ]);
    }

    public function exists(Request $request)
    {
        $data = $request->validate([
            'account_id' => ['required', 'integer', 'exists:users,id'],
            'creature_uid' => ['required', 'string', 'max:120'],
        ]);

        $this->authorizeAccount((int) $data['account_id'], $request);
        $entry = $this->findEntry((int) $data['account_id'], $data['creature_uid']);

        return response()->json([
            'account_id' => (int) $data['account_id'],
            'creature_uid' => $data['creature_uid'],
            'exists' => (bool) $entry,
            'entry' => $entry ? $this->entries->exportToJson($entry) : null,
        ]);
    }

    public function diff(Request $request)
    {
        $data = $request->validate([
            'account_id' => ['required', 'integer', 'exists:users,id'],
            'local_creature_uids' => ['present', 'array'],
            'local_creature_uids.*' => ['string', 'max:120'],
        ]);

        $accountId = (int) $data['account_id'];
        $this->authorizeAccount($accountId, $request);

        $localUids = collect($data['local_creature_uids'])->unique()->values();
        $serverEntries = BestiaryEntry::where('user_id', $accountId)
            ->with(['dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes'])
            ->get();
        $serverUids = $serverEntries->pluck('sync_uid')->filter()->values();

        return response()->json([
            'account_id' => $accountId,
            'server_time' => now()->toISOString(),
            'existing_uids' => $localUids->intersect($serverUids)->values(),
            'missing_on_device' => $serverEntries
                ->whereNotIn('sync_uid', $localUids)
                ->map(fn (BestiaryEntry $entry) => $this->entries->exportToJson($entry))
                ->values(),
            'missing_on_server_uids' => $localUids->diff($serverUids)->values(),
        ]);
    }

    public function upsert(Request $request)
    {
        $data = $request->validate([
            'account_id' => ['required', 'integer', 'exists:users,id'],
            'creature_uid' => ['required', 'string', 'max:120'],
            'entry' => ['required', 'array'],
            'entry.title' => ['required', 'string', 'max:255'],
            'entry.classification' => ['required', 'string', 'max:255'],
            'entry.threat_level' => ['required', 'string', 'max:255'],
            'entry.description' => ['required', 'string'],
            'entry.theme_key' => ['nullable', Rule::exists('dossier_themes', 'key')],
        ]);

        $accountId = (int) $data['account_id'];
        $this->authorizeAccount($accountId, $request);

        $existed = BestiaryEntry::where('user_id', $accountId)
            ->where('sync_uid', $data['creature_uid'])
            ->exists();
        $entry = $this->entries->upsertForSync($data['entry'], $accountId, $data['creature_uid']);

        return response()->json([
            'account_id' => $accountId,
            'creature_uid' => $data['creature_uid'],
            'entry' => $this->entries->exportToJson($entry),
        ], $existed ? 200 : 201);
    }

    private function validatedAccountId(Request $request): int
    {
        $data = $request->validate([
            'account_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $accountId = (int) $data['account_id'];
        $this->authorizeAccount($accountId, $request);

        return $accountId;
    }

    private function authorizeAccount(int $accountId, Request $request): void
    {
        abort_unless($request->user()->id === $accountId || $request->user()->hasRole('admin'), 403, 'El token no pertenece a esta cuenta.');
    }

    private function findEntry(int $accountId, string $creatureUid): ?BestiaryEntry
    {
        return BestiaryEntry::where('user_id', $accountId)
            ->where('sync_uid', $creatureUid)
            ->with(['dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes'])
            ->first();
    }
}
