<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BestiaryEntry;
use App\Models\DossierTheme;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $userId = request()->user()->id;
        $entries = BestiaryEntry::where('user_id', $userId);

        return view('dashboard', [
            'totalEntries' => (clone $entries)->count(),
            'byThreat' => (clone $entries)->selectRaw('threat_level, count(*) as total')->groupBy('threat_level')->pluck('total', 'threat_level'),
            'byTheme' => DossierTheme::withCount(['entries' => fn ($query) => $query->where('user_id', $userId)])->orderBy('name')->get(),
            'latestEntries' => (clone $entries)->latest('updated_at')->limit(6)->get(),
        ]);
    }
}
