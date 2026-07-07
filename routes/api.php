<?php

use App\Http\Controllers\Api\AiGenerationController;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\BestiaryEntryController;
use App\Http\Controllers\Api\CreatureSyncController;
use App\Http\Controllers\Api\DossierThemeController;
use App\Http\Controllers\Api\JsonImportController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthTokenController::class, 'register']);
Route::post('/auth/login', [AuthTokenController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthTokenController::class, 'me']);
    Route::post('/auth/logout', [AuthTokenController::class, 'logout']);

    Route::apiResource('entries', BestiaryEntryController::class)->names('api.entries');
    Route::get('/entries/{entry}/export-json', [BestiaryEntryController::class, 'exportJson']);
    Route::get('/themes', [DossierThemeController::class, 'index']);
    Route::post('/themes', [DossierThemeController::class, 'store']);
    Route::post('/import-json', JsonImportController::class);
    Route::post('/generate-creature', AiGenerationController::class);

    Route::get('/sync/creatures', [CreatureSyncController::class, 'index']);
    Route::get('/sync/creatures/{creatureUid}', [CreatureSyncController::class, 'show']);
    Route::post('/sync/creatures/exists', [CreatureSyncController::class, 'exists']);
    Route::post('/sync/creatures/diff', [CreatureSyncController::class, 'diff']);
    Route::post('/sync/creatures/upsert', [CreatureSyncController::class, 'upsert']);
});
