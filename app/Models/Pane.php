<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pane extends Model
{
    protected $fillable = [
        'name', 'description', 'text', 'font', 'size', 'colour', 'bgColour',
        'top', 'left', 'width', 'height',
        'animationIn', 'animationOut', 'showFor',
        'alwaysShown', 'extraCss', 'extraClasses',
    ];

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
