<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['dossier_theme_id', 'type', 'path', 'width', 'height'])]
class DossierThemeAsset extends Model
{
    public function dossierTheme(): BelongsTo { return $this->belongsTo(DossierTheme::class); }
}
