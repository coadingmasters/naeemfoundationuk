<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Resolves a raw video URL (YouTube/Vimeo link or an uploaded file path) into
 * something renderable. Mirrors the model trait, but works with plain strings
 * so views/config can use it without an Eloquent model.
 */
class VideoSource
{
    /** True when the URL is a YouTube/Vimeo link (embed via iframe). */
    public static function isEmbed(?string $url): bool
    {
        return (bool) preg_match('#(youtube\.com|youtu\.be|vimeo\.com)#i', (string) $url);
    }

    /** A ready-to-embed URL for YouTube/Vimeo; the raw URL otherwise. */
    public static function embedUrl(?string $url, bool $autoplay = false): string
    {
        $url = (string) $url;
        $id = null;

        if (preg_match('#youtu\.be/([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/watch\?v=([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/embed/([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/shorts/([\w-]+)#i', $url, $m)) {
            $id = 'https://www.youtube.com/embed/'.$m[1];
        } elseif (preg_match('#vimeo\.com/(\d+)#i', $url, $m)) {
            $id = 'https://player.vimeo.com/video/'.$m[1];
        }

        if (! $id) {
            return $url;
        }

        return $autoplay ? $id.'?autoplay=1&rel=0' : $id;
    }

    /** Resolve a stored relative path to a full URL; leave absolute URLs as-is. */
    public static function playableUrl(?string $url): string
    {
        $url = (string) $url;

        return Str::startsWith($url, ['http://', 'https://', '//']) ? $url : asset($url);
    }
}
