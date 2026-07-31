<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Orphan extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    protected $fillable = [
        'region', 'name', 'slug', 'location', 'grade', 'dob', 'story',
        'photo', 'monthly_amount', 'sort_order', 'is_active',
    ];

    /** Route model binding resolves orphans by their slug, not their id. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Auto-generate a unique slug from the name when one isn't set. */
    protected static function booted(): void
    {
        static::saving(function (self $orphan) {
            if (blank($orphan->slug) && filled($orphan->name)) {
                $orphan->slug = self::uniqueSlug($orphan->name, $orphan->id);
            }
        });
    }

    public static function uniqueSlug(string $name, $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'orphan';
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
            'monthly_amount' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /** The cause string a donation for this orphan is tagged with. */
    public function causeLabel(): string
    {
        return 'Sponsor '.$this->name;
    }
}
