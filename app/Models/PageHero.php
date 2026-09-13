<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * An admin-uploaded photo that overrides the built-in hero background for one
 * Giving page. The page is identified by `page_key`, which matches the page's
 * route name. Resolved for the front end by App\Support\PageHeroes.
 */
class PageHero extends Model
{
    protected $fillable = [
        'page_key',
        'image',
        'mobile_image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
