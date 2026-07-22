<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    /** Shop categories used across the store + admin. */
    public const CATEGORIES = ['Clothing', 'Books', 'Gifts', 'Ramadan', 'Home & Living'];

    protected $fillable = [
        'region',
        'name',
        'slug',
        'description',
        'category',
        'price',
        'price_usd',
        'price_cad',
        'image',
        'badge',
        'in_stock',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_usd' => 'decimal:2',
            'price_cad' => 'decimal:2',
            'in_stock' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** Price for a region code — defaults to the product's OWN region (falls back to base). */
    public function priceFor(?string $code = null): float
    {
        $code = $code ?: ($this->region ?: \App\Support\Country::code());

        return (float) match ($code) {
            'US' => $this->price_usd ?? $this->price,
            'CA' => $this->price_cad ?? $this->price,
            default => $this->price,
        };
    }

    /** The product's price formatted in its own region's currency (for admin lists etc.). */
    public function displayPrice(): string
    {
        $symbol = config('countries.list.'.$this->region.'.symbol', '£');

        return $symbol.number_format($this->priceFor($this->region), 2);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            if (blank($product->slug) && filled($product->name)) {
                $product->slug = self::uniqueSlug($product->name, $product->id);
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    protected static function uniqueSlug(string $name, $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $n = 2;

        while (self::where('slug', $slug)
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$n++;
        }

        return $slug;
    }
}
