<?php

namespace App\Services;

use App\Models\BestiaryEntry;
use Illuminate\Support\Facades\Storage;

class DossierExportService
{
    public function exportPng(BestiaryEntry $entry): string
    {
        $path = "exports/dossiers/{$entry->slug}.png";
        Storage::disk('public')->put($path, 'PNG export placeholder. Configure a renderer such as Browsershot in production.');

        return $path;
    }

    public function exportPdf(BestiaryEntry $entry): string
    {
        $path = "exports/dossiers/{$entry->slug}.pdf";
        Storage::disk('public')->put($path, 'PDF export placeholder. Configure a renderer such as Browsershot or DomPDF in production.');

        return $path;
    }
}
