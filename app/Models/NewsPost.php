<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    /** The categories an editor can choose from. */
    public const CATEGORIES = ['News', 'Press Release', 'Blog', 'Emergency'];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'body',
        'image',
        'published_at',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Only posts that should appear on the public site. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Newest first. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    /** Public image URL, or null when none was uploaded. */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return Str::startsWith($this->image, ['http://', 'https://', '//'])
            ? $this->image
            : asset($this->image);
    }

    /** A short teaser, falling back to the body when no excerpt was written. */
    public function getTeaserAttribute(): string
    {
        return $this->excerpt ?: Str::limit(strip_tags((string) $this->body), 160);
    }

    /** Roughly how long the article takes to read. */
    public function getReadMinutesAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));

        return max(1, (int) ceil($words / 200));
    }

    /** Build a unique slug from a title. */
    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
