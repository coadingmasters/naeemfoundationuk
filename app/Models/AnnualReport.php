<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AnnualReport extends Model
{
    protected $fillable = [
        'title',
        'year',
        'summary',
        'file_path',
        'cover_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** Only reports that should appear on the public site. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Display order: explicit sort_order first, then newest year. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('year')->orderByDesc('id');
    }

    /** Public URL of the PDF. */
    public function getFileUrlAttribute(): string
    {
        return $this->toUrl($this->file_path);
    }

    /** Public URL of the cover image, if one was uploaded. */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_path ? $this->toUrl($this->cover_path) : null;
    }

    private function toUrl(?string $path): string
    {
        $path = (string) $path;

        return Str::startsWith($path, ['http://', 'https://', '//']) ? $path : asset($path);
    }
}
