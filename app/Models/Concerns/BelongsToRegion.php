<?php

namespace App\Models\Concerns;

use App\Support\RegionContext;
use Illuminate\Database\Eloquent\Builder;

/**
 * Makes a model region-owned:
 *  - reads are automatically filtered to the active region (RegionContext),
 *    unless a super admin is viewing "all regions";
 *  - new records are stamped with the active region on create.
 *
 * Use `Model::withoutGlobalScope('region')` (or ::allRegions()) to bypass.
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
