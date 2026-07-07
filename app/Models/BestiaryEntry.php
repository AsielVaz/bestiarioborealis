<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'dossier_theme_id', 'title', 'slug', 'classification', 'category',
    'sync_uid', 'threat_level', 'height', 'description', 'last_record', 'status',
    'final_combat_scenario', 'main_image_path', 'primary_color', 'accent_color',
    'parchment_tone', 'frame_style', 'published_at', 'last_synced_at',
])]
class BestiaryEntry extends Model
{
    /** @use HasFactory<\Database\Factories\BestiaryEntryFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'last_synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function dossierTheme(): BelongsTo { return $this->belongsTo(DossierTheme::class); }
    public function origin(): HasOne { return $this->hasOne(EntryOrigin::class); }
    public function subtitles(): HasMany { return $this->hasMany(EntrySubtitle::class)->orderBy('sort_order'); }
    public function affinities(): HasMany { return $this->hasMany(EntryAffinity::class)->orderBy('sort_order'); }
    public function habitats(): HasMany { return $this->hasMany(EntryHabitat::class)->orderBy('sort_order'); }
    public function behaviors(): HasMany { return $this->hasMany(EntryBehavior::class)->orderBy('sort_order'); }
    public function abilities(): HasMany { return $this->hasMany(EntryAbility::class)->orderBy('sort_order'); }
    public function techniques(): HasMany { return $this->hasMany(EntryTechnique::class)->orderBy('sort_order'); }
    public function weaknesses(): HasMany { return $this->hasMany(EntryWeakness::class)->orderBy('sort_order'); }
    public function loot(): HasMany { return $this->hasMany(EntryLoot::class)->orderBy('sort_order'); }
    public function stats(): HasMany { return $this->hasMany(EntryStat::class)->orderBy('sort_order'); }
    public function vignettes(): HasMany { return $this->hasMany(EntryVignette::class)->orderBy('sort_order'); }
    public function scholarNotes(): HasMany { return $this->hasMany(EntryScholarNote::class)->orderBy('sort_order'); }
}
