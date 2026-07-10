<?php

namespace App\Models;

use App\Models\Concerns\ResolvesVideoUrl;
use Illuminate\Database\Eloquent\Model;

class CommunityVideo extends Model
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
