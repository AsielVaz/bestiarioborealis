<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportBestiaryJsonRequest;
use App\Services\BestiaryEntryService;

class JsonImportController extends Controller
{
    public function __invoke(ImportBestiaryJsonRequest $request, BestiaryEntryService $entries)
    {
        return response()->json($entries->createFromJson($request->payload(), $request->user()?->id), 201);
    }
}
