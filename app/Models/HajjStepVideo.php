<?php

namespace App\Models;

use App\Models\Concerns\ResolvesVideoUrl;
use Illuminate\Database\Eloquent\Model;

/**
 * A video shown in the "Steps of Hajj" gallery on the Hajj page — an open,
 * admin-managed list (add/edit/delete as many as needed), same shape as
 * HajjVideo.
 */
class HajjStepVideo extends Model
{
    use ResolvesVideoUrl;

    protected $fillable = [
        'title',
        'video_url',
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
}
