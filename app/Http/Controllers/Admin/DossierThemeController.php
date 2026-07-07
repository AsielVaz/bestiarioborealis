<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DossierTheme;
use Illuminate\Http\Request;

class DossierThemeController extends Controller
{
    public function index()
    {
        return view('admin.themes.index', ['themes' => DossierTheme::withCount('entries')->orderBy('name')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.themes.create');
    }

    public function store(Request $request)
    {
        DossierTheme::create($this->validateTheme($request));

        return redirect()->route('themes.index')->with('status', 'Tema creado.');
    }

    public function edit(DossierTheme $theme)
    {
        return view('admin.themes.edit', compact('theme'));
    }

    public function update(Request $request, DossierTheme $theme)
    {
        $theme->update($this->validateTheme($request, $theme));

        return redirect()->route('themes.index')->with('status', 'Tema actualizado.');
    }

    public function destroy(DossierTheme $theme)
    {
        $theme->delete();

        return redirect()->route('themes.index')->with('status', 'Tema archivado.');
    }

    private function validateTheme(Request $request, ?DossierTheme $theme = null): array
    {
        return $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:dossier_themes,key,'.($theme?->id ?? 'NULL')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'primary_color' => ['required', 'string', 'max:20'],
            'accent_color' => ['required', 'string', 'max:20'],
            'stat_color' => ['nullable', 'string', 'max:20'],
            'seal_color' => ['nullable', 'string', 'max:20'],
            'parchment_tone' => ['required', 'string', 'max:20'],
            'ink_color' => ['nullable', 'string', 'max:20'],
            'muted_ink' => ['nullable', 'string', 'max:20'],
            'frame_style' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
