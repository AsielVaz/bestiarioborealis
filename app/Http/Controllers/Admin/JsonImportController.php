<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportBestiaryJsonRequest;
use App\Services\BestiaryEntryService;

class JsonImportController extends Controller
{
    public function create()
    {
        return view('admin.import');
    }

    public function store(ImportBestiaryJsonRequest $request, BestiaryEntryService $entries)
    {
        $entry = $entries->createFromJson($request->payload(), $request->user()->id);

        return redirect()->route('entries.show', $entry)->with('status', 'JSON importado.');
    }

    public function catalog(BestiaryEntryService $entries)
    {
        return response()->json(
            request()->user()->bestiaryEntries()->get()->map(fn ($entry) => $entries->exportToJson($entry))
        );
    }
}
