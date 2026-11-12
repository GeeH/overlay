<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    protected $fillable = ['name', 'theme', 'top', 'left', 'width', 'scale', 'auto_hide'];

    protected function casts(): array
    {
        return ['auto_hide' => 'boolean'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('position')->orderBy('id');
    }

    /**
     * Theme names, one per Blade view in resources/views/checklist-themes.
     * Add a theme by adding a view there.
     *
     * @return list<string>
     */
    public static function themes(): array
    {
        return collect(glob(resource_path('views/checklist-themes/*.blade.php')))
            ->map(fn (string $path) => basename($path, '.blade.php'))
            ->sort()
            ->values()
            ->all();
    }
}
