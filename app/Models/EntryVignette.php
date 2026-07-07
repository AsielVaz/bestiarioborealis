<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['bestiary_entry_id', 'title', 'description', 'image_path', 'sort_order'])]
class EntryVignette extends Model
{
    public function bestiaryEntry(): BelongsTo { return $this->belongsTo(BestiaryEntry::class); }
}
