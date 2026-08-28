<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Shared behaviour for models that hold a single video URL, which may be a
 * YouTube/Vimeo/Facebook link (embedded via iframe) or an uploaded file path.
 */
trait ResolvesVideoUrl
{
    /** Only videos that should appear on the public site. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Display order: explicit sort_order first, then newest. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /** True when the URL is a YouTube/Vimeo/Facebook link (embed via iframe). */
    public function getIsEmbedAttribute(): bool
    {
        return (bool) preg_match('#(youtube\.com|youtu\.be|vimeo\.com|facebook\.com|fb\.watch)#i', (string) $this->video_url);
    }

    /** A ready-to-embed URL for YouTube/Vimeo/Facebook; the raw URL/path otherwise. */
    public function getEmbedUrlAttribute(): string
    {
        $url = (string) $this->video_url;

        if (preg_match('#youtu\.be/([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/watch\?v=([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/embed/([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/shorts/([\w-]+)#i', $url, $m)) {
            return 'https://www.youtube.com/embed/'.$m[1];
        }

        if (preg_match('#vimeo\.com/(\d+)#i', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        if (preg_match('#(facebook\.com|fb\.watch)#i', $url)) {
            return 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($url).'&show_text=false';
        }

        return $url;
    }

    /** Resolve a stored relative path to a full URL; leave absolute URLs as-is. */
    public function getPlayableUrlAttribute(): string
    {
        $url = (string) $this->video_url;

        return Str::startsWith($url, ['http://', 'https://', '//']) ? $url : asset($url);
    }
}
