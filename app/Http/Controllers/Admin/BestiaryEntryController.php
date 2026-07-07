<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBestiaryEntryRequest;
use App\Http\Requests\UpdateBestiaryEntryRequest;
use App\Models\BestiaryEntry;
use App\Models\DossierTheme;
use App\Services\BestiaryEntryService;
use Illuminate\Http\Request;

class BestiaryEntryController extends Controller
{
    public function __construct(private readonly BestiaryEntryService $entries)
    {
    }

    public function index(Request $request)
    {
        $entries = BestiaryEntry::query()
            ->with('dossierTheme')
            ->when($request->search, fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->when($request->classification, fn ($q, $value) => $q->where('classification', $value))
            ->when($request->category, fn ($q, $value) => $q->where('category', $value))
            ->when($request->threat_level, fn ($q, $value) => $q->where('threat_level', $value))
            ->when($request->status, fn ($q, $value) => $q->where('status', $value))
            ->when($request->theme, fn ($q, $value) => $q->whereHas('dossierTheme', fn ($theme) => $theme->where('key', $value)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.entries.index', compact('entries'));
    }

    public function create()
    {
        return view('admin.entries.create', ['themes' => DossierTheme::where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(StoreBestiaryEntryRequest $request)
    {
        $entry = $this->entries->createFromRequest($request);

        return redirect()->route('entries.show', $entry)->with('status', 'Ficha creada.');
    }

    public function show(BestiaryEntry $entry)
    {
        $entry->load(['dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes']);

        return view('admin.entries.show', compact('entry'));
    }

    public function edit(BestiaryEntry $entry)
    {
        $entry->load(['origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes']);

        return view('admin.entries.edit', ['entry' => $entry, 'themes' => DossierTheme::where('is_active', true)->orderBy('name')->get()]);
    }

    public function update(UpdateBestiaryEntryRequest $request, BestiaryEntry $entry)
    {
        $this->entries->updateFromRequest($request, $entry);

        return redirect()->route('entries.show', $entry)->with('status', 'Ficha actualizada.');
    }

    public function destroy(BestiaryEntry $entry)
    {
        $entry->delete();

        return redirect()->route('entries.index')->with('status', 'Ficha archivada.');
    }
}
