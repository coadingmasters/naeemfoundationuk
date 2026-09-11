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

    /**
     * Facebook's embed plugin needs the canonical video URL (…/reel/{id},
     * …/videos/{id}, …/watch/?v={id}) — a facebook.com/share/... shortlink
     * doesn't resolve inside the iframe, so the embed shows "Video
     * unavailable" even though the link opens fine in a real browser.
     *
     * Called once, when an admin saves a link (not on every page view):
     * follows the shortlink's redirect and stores the canonical URL it
     * lands on instead. Falls back to the original link on any failure
     * (Facebook down, no curl, etc.) so saving never hard-fails.
     */
    public static function resolveShareLink(string $url): string
    {
        if (! preg_match('#facebook\.com/share/#i', $url) || ! function_exists('curl_init')) {
            return $url;
        }

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_NOBODY => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 8,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; NaeemFoundationBot/1.0; +https://naeemfoundation.co.uk)',
            ]);
            curl_exec($ch);
            $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $failed = curl_errno($ch) !== 0;
            curl_close($ch);

            if (! $failed && is_string($effectiveUrl) && str_contains($effectiveUrl, 'facebook.com')) {
                // Drop Facebook's tracking query string (mibextid, rdid,
                // share_url…) — the plugin only needs the canonical path.
                return strtok($effectiveUrl, '?') ?: $effectiveUrl;
            }
        } catch (\Throwable $e) {
            // fall through to the original link
        }

        return $url;
    }
}
