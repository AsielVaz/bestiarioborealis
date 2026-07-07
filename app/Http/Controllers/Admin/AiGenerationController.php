<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateCreatureRequest;
use App\Services\CreatureGenerationService;

class AiGenerationController extends Controller
{
    public function create()
    {
        return view('admin.generate');
    }

    public function store(GenerateCreatureRequest $request, CreatureGenerationService $generator)
    {
        $entry = $generator->generateFromDescription($request->validated('description'), $request->user()->id);

        return redirect()->route('entries.show', $entry)->with('status', 'Creatura generada.');
    }
}
