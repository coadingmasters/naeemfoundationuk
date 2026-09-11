<?php

namespace App\Models;

use App\Models\Concerns\ResolvesVideoUrl;
use Illuminate\Database\Eloquent\Model;

/**
 * An admin-uploaded video for one of the 8 "Steps of Hajj" (see
 * App\Support\HajjSteps). Replaces that step's plain description card with a
 * video, gallery-style, on the public Hajj page.
 */
class HajjStepVideo extends Model
{
    use ResolvesVideoUrl;

    protected $fillable = [
        'step_key',
        'video_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
