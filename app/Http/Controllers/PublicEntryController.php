<?php

namespace App\Http\Controllers;

use App\Models\BestiaryEntry;

class PublicEntryController extends Controller
{
    public function show(string $slug)
    {
        $entry = BestiaryEntry::with(['dossierTheme', 'subtitles', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes'])
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('public.entry', compact('entry'));
    }
}
