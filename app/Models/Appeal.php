<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appeal extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    protected $fillable = [
        'region',
        'title',
        'slug',
        'description',
        'image',
        'link',
        'sort_order',
        'is_active',
    ];

    /** Route model binding resolves appeals by their slug, not their id. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Auto-generate a unique slug from the title when one isn't set. */
    protected static function booted(): void
    {
        static::saving(function (self $appeal) {
            if (blank($appeal->slug) && filled($appeal->title)) {
                $appeal->slug = self::uniqueSlug($appeal->title, $appeal->id);
            }
        });
    }

    protected static function uniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'appeal';
        $slug = $base;
        $n = 2;

        while (self::where('slug', $slug)
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$n++;
        }

        return $slug;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** Only appeals that should appear on the public site. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Display order: explicit sort_order first, then newest. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }
}
