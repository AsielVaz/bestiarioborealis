<?php

use App\Http\Controllers\Admin\AiGenerationController;
use App\Http\Controllers\Admin\BestiaryEntryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DossierController;
use App\Http\Controllers\Admin\DossierThemeController;
use App\Http\Controllers\Admin\JsonImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicEntryController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::get('/bestiary/{slug}', [PublicEntryController::class, 'show'])->name('public.entries.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('entries', BestiaryEntryController::class);
    Route::resource('themes', DossierThemeController::class)->except('show');

    Route::get('/import-json', [JsonImportController::class, 'create'])->name('import.create');
    Route::post('/import-json', [JsonImportController::class, 'store'])->name('import.store');
    Route::get('/export-catalog-json', [JsonImportController::class, 'catalog'])->name('catalog.export');

    Route::get('/generate-creature', [AiGenerationController::class, 'create'])->name('generate.create');
    Route::post('/generate-creature', [AiGenerationController::class, 'store'])->name('generate.store');

    Route::get('/entries/{entry}/dossier', [DossierController::class, 'show'])->name('entries.dossier');
    Route::get('/entries/{entry}/export-json', [DossierController::class, 'exportJson'])->name('entries.export-json');
    Route::get('/entries/{entry}/export-{format}', [DossierController::class, 'export'])->name('entries.export-dossier');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
