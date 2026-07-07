<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BestiaryEntry;
use App\Services\BestiaryEntryService;
use App\Services\DossierExportService;
use Illuminate\Support\Facades\Storage;

class DossierController extends Controller
{
    public function show(BestiaryEntry $entry)
    {
        $this->authorizeEntry($entry);

        $entry->load(['dossierTheme', 'origin', 'subtitles', 'affinities', 'habitats', 'behaviors', 'abilities', 'techniques', 'weaknesses', 'loot', 'stats', 'vignettes', 'scholarNotes']);

        return view('admin.dossier', compact('entry'));
    }

    public function exportJson(BestiaryEntry $entry, BestiaryEntryService $entries)
    {
        $this->authorizeEntry($entry);

        return response()->json($entries->exportToJson($entry));
    }

    public function export(BestiaryEntry $entry, string $format, DossierExportService $exporter)
    {
        $this->authorizeEntry($entry);

        abort_unless(in_array($format, ['png', 'pdf'], true), 404);

        $path = $format === 'png' ? $exporter->exportPng($entry) : $exporter->exportPdf($entry);

        return Storage::disk('public')->download($path);
    }

    private function authorizeEntry(BestiaryEntry $entry): void
    {
        abort_unless(request()->user()->id === $entry->user_id, 403);
    }
}
