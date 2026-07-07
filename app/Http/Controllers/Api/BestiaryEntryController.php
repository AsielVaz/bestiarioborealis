<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBestiaryEntryRequest;
use App\Http\Requests\UpdateBestiaryEntryRequest;
use App\Models\BestiaryEntry;
use App\Services\BestiaryEntryService;
use Illuminate\Http\Request;

class BestiaryEntryController extends Controller
{
    public function __construct(private readonly BestiaryEntryService $entries)
    {
    }

    public function index(Request $request)
    {
        return BestiaryEntry::with(['dossierTheme', 'subtitles', 'affinities', 'stats'])
            ->when(! $request->user()->hasRole('admin'), fn ($query) => $query->where('user_id', $request->user()->id))
            ->when($request->search, fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(25);
    }

    public function store(StoreBestiaryEntryRequest $request)
    {
        return response()->json($this->entries->createFromRequest($request)->load('dossierTheme'), 201);
    }

    public function show(BestiaryEntry $entry)
    {
        $this->authorizeEntry($entry);

        return $entry->load(['dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes']);
    }

    public function update(UpdateBestiaryEntryRequest $request, BestiaryEntry $entry)
    {
        $this->authorizeEntry($entry);

        return $this->entries->updateFromRequest($request, $entry)->load('dossierTheme');
    }

    public function destroy(BestiaryEntry $entry)
    {
        $this->authorizeEntry($entry);

        $entry->delete();

        return response()->noContent();
    }

    public function exportJson(BestiaryEntry $entry)
    {
        $this->authorizeEntry($entry);

        return response()->json($this->entries->exportToJson($entry));
    }

    private function authorizeEntry(BestiaryEntry $entry): void
    {
        abort_unless(request()->user()->id === $entry->user_id || request()->user()->hasRole('admin'), 403);
    }
}
