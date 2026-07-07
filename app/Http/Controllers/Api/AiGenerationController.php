<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateCreatureRequest;
use App\Services\CreatureGenerationService;

class AiGenerationController extends Controller
{
    public function __invoke(GenerateCreatureRequest $request, CreatureGenerationService $generator)
    {
        return response()->json(
            $generator->generateFromDescription($request->validated('description'), $request->user()?->id),
            201
        );
    }
}
