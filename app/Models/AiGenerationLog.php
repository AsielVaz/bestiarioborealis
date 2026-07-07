<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'bestiary_entry_id', 'provider', 'model', 'prompt', 'response',
    'status', 'error_message', 'input_tokens', 'output_tokens',
])]
class AiGenerationLog extends Model
{
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function bestiaryEntry(): BelongsTo { return $this->belongsTo(BestiaryEntry::class); }
}
