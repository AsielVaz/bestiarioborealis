<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'key', 'name', 'description', 'primary_color', 'accent_color', 'stat_color',
    'seal_color', 'parchment_tone', 'ink_color', 'muted_ink', 'frame_style', 'is_active',
])]
class DossierTheme extends Model
{
    /** @use HasFactory<\Database\Factories\DossierThemeFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function entries(): HasMany { return $this->hasMany(BestiaryEntry::class); }
    public function assets(): HasMany { return $this->hasMany(DossierThemeAsset::class); }
}
