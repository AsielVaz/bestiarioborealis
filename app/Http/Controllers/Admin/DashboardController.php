<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BestiaryEntry;
use App\Models\DossierTheme;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'totalEntries' => BestiaryEntry::count(),
            'byThreat' => BestiaryEntry::selectRaw('threat_level, count(*) as total')->groupBy('threat_level')->pluck('total', 'threat_level'),
            'byTheme' => DossierTheme::withCount('entries')->orderBy('name')->get(),
            'latestEntries' => BestiaryEntry::latest('updated_at')->limit(6)->get(),
        ]);
    }
}
