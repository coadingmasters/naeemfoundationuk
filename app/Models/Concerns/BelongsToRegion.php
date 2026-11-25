<?php

namespace App\Models\Concerns;

use App\Support\RegionContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Makes a model region-owned:
 *  - reads are automatically filtered to the active region (RegionContext),
 *    unless a super admin is viewing "all regions";
 *  - new records are stamped with the active region on create.
 *
 * Use `Model::withoutGlobalScope('region')` (or ::allRegions()) to bypass.
 *
 * @mixin Model
 */
trait BelongsToRegion
{
    protected static function bootBelongsToRegion(): void
    {
        static::addGlobalScope('region', function (Builder $builder) {
            if (RegionContext::hasScope()) {
                $builder->where($builder->getModel()->getTable().'.region', RegionContext::region());
            }
        });

        static::creating(function ($model) {
            if (empty($model->region) && RegionContext::region() !== null) {
                $model->region = RegionContext::region();
            }
        });

        // The site always writes "Healthcare" as one word, however it is typed in the admin.
        static::saving(function ($model) {
            foreach (['title', 'name'] as $field) {
                $value = $model->getAttribute($field);

                if (is_string($value) && preg_match('/health[ -]care/i', $value)) {
                    $model->setAttribute($field, preg_replace_callback(
                        '/(health)[ -](care)/i',
                        fn ($m) => $m[1].(mb_strtoupper($m[1]) === $m[1] ? $m[2] : strtolower($m[2])),
                        $value
                    ));
                }
            }
        });
    }

    /** Query across every region regardless of the current scope. */
    public function scopeAllRegions(Builder $query): Builder
    {
        return $query->withoutGlobalScope('region');
    }

    /** Query a specific region regardless of the current scope. */
    public function scopeForRegion(Builder $query, string $region): Builder
    {
        return $query->withoutGlobalScope('region')->where($this->getTable().'.region', $region);
    }
}
