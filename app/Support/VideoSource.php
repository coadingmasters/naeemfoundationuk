<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Resolves a raw video URL (YouTube/Vimeo/Facebook link or an uploaded file
 * path) into something renderable. Mirrors the model trait, but works with
 * plain strings so views/config can use it without an Eloquent model.
 */
class VideoSource
{
    /** True when the URL is a YouTube/Vimeo/Facebook link (embed via iframe). */
    public static function isEmbed(?string $url): bool
    {
        return (bool) preg_match('#(youtube\.com|youtu\.be|vimeo\.com|facebook\.com|fb\.watch)#i', (string) $url);
    }

    /** True when the URL points at Facebook (reel / watch / video / fb.watch). */
    public static function isFacebook(?string $url): bool
    {
        return (bool) preg_match('#(facebook\.com|fb\.watch)#i', (string) $url);
    }

    /**
     * A ready-to-embed URL for YouTube/Vimeo/Facebook; the raw URL otherwise.
     */
    public static function embedUrl(?string $url, bool $autoplay = false): string
    {
        $url = (string) $url;

        if (preg_match('#youtu\.be/([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/watch\?v=([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/embed/([\w-]+)#i', $url, $m)
            || preg_match('#youtube\.com/shorts/([\w-]+)#i', $url, $m)) {
            $embed = 'https://www.youtube.com/embed/'.$m[1];

            return $autoplay ? $embed.'?autoplay=1&rel=0' : $embed;
        }

        if (preg_match('#vimeo\.com/(\d+)#i', $url, $m)) {
            $embed = 'https://player.vimeo.com/video/'.$m[1];

            return $autoplay ? $embed.'?autoplay=1&rel=0' : $embed;
        }

        if (self::isFacebook($url)) {
            $embed = 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($url).'&show_text=false';

            return $autoplay ? $embed.'&autoplay=1&mute=1' : $embed;
        }

        return $url;
    }

    /** Resolve a stored relative path to a full URL; leave absolute URLs as-is. */
    public static function playableUrl(?string $url): string
    {
        $url = (string) $url;

        return Str::startsWith($url, ['http://', 'https://', '//']) ? $url : asset($url);
    }
}
