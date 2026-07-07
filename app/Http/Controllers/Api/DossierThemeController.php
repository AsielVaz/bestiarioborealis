<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DossierTheme;
use Illuminate\Http\Request;

class DossierThemeController extends Controller
{
    public function index()
    {
        return DossierTheme::with('assets')->where('is_active', true)->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:dossier_themes,key'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'primary_color' => ['required', 'string', 'max:20'],
            'accent_color' => ['required', 'string', 'max:20'],
            'parchment_tone' => ['required', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        return response()->json(DossierTheme::create($data), 201);
    }
}
