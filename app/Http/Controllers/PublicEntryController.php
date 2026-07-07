<?php

namespace App\Http\Controllers;

use App\Models\BestiaryEntry;

class PublicEntryController extends Controller
{
    public function show(string $slug)
    {
        $entry = BestiaryEntry::with(['dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes'])
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        abort_unless(request()->user()->id === $entry->user_id, 403);

        return view('public.entry', compact('entry'));
    }
}
