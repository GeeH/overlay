<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    protected $fillable = ['text', 'done', 'position'];

    protected function casts(): array
    {
        return ['done' => 'boolean'];
    }
}
