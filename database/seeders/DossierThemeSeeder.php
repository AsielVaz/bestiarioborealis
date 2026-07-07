<?php

namespace Database\Seeders;

use App\Models\DossierTheme;
use Illuminate\Database\Seeder;

class DossierThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            ['key' => 'arcane', 'name' => 'Arcano oscuro', 'primary_color' => '#4c1d95', 'accent_color' => '#d6ad60', 'stat_color' => '#a78bfa', 'seal_color' => '#f5c56b', 'parchment_tone' => '#ead8b7', 'ink_color' => '#21180f', 'muted_ink' => '#6f5a44', 'frame_style' => 'runic'],
            ['key' => 'electro', 'name' => 'Electro Inazuma', 'primary_color' => '#6d28d9', 'accent_color' => '#67e8f9', 'stat_color' => '#c084fc', 'seal_color' => '#22d3ee', 'parchment_tone' => '#e9ddff', 'ink_color' => '#201133', 'muted_ink' => '#6b5a7f', 'frame_style' => 'storm'],
            ['key' => 'marine', 'name' => 'Marino Watatsumi', 'primary_color' => '#0f766e', 'accent_color' => '#38bdf8', 'stat_color' => '#2dd4bf', 'seal_color' => '#93c5fd', 'parchment_tone' => '#dff6f3', 'ink_color' => '#0f2f35', 'muted_ink' => '#4d7374', 'frame_style' => 'tidal'],
            ['key' => 'fire', 'name' => 'Fuego corrupto', 'primary_color' => '#991b1b', 'accent_color' => '#f59e0b', 'stat_color' => '#fb7185', 'seal_color' => '#fbbf24', 'parchment_tone' => '#f2d4aa', 'ink_color' => '#2d1209', 'muted_ink' => '#7a4b35', 'frame_style' => 'ember'],
            ['key' => 'holy', 'name' => 'Sagrado', 'primary_color' => '#b45309', 'accent_color' => '#fef3c7', 'stat_color' => '#facc15', 'seal_color' => '#fde68a', 'parchment_tone' => '#fff7df', 'ink_color' => '#3b2a11', 'muted_ink' => '#8a744d', 'frame_style' => 'halo'],
            ['key' => 'shadow', 'name' => 'Sombra / clandestino', 'primary_color' => '#111827', 'accent_color' => '#9ca3af', 'stat_color' => '#6b7280', 'seal_color' => '#c084fc', 'parchment_tone' => '#d8d2c4', 'ink_color' => '#111111', 'muted_ink' => '#58514a', 'frame_style' => 'cipher'],
        ];

        foreach ($themes as $theme) {
            DossierTheme::updateOrCreate(['key' => $theme['key']], $theme + ['is_active' => true]);
        }
    }
}
