<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pane extends Model
{
    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
