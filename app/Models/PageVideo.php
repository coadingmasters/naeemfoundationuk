<?php

namespace App\Models;

use App\Models\Concerns\ResolvesVideoUrl;
use Illuminate\Database\Eloquent\Model;

/**
 * An admin-set video that overrides the built-in default for one Giving page.
 * The page is identified by `page_key`, which matches a key in
 * config/appeal-videos.php. Resolved for the front end by App\Support\PageVideos.
 */
class PageVideo extends Model
{
    use ResolvesVideoUrl;

    protected $fillable = [
        'page_key',
        'title',
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
