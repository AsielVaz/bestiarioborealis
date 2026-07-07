<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['bestiary_entry_id', 'universe', 'game', 'campaign', 'source', 'region'])]
class EntryOrigin extends Model
{
    public function bestiaryEntry(): BelongsTo { return $this->belongsTo(BestiaryEntry::class); }
}
