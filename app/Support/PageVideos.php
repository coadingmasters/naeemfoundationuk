<?php

namespace App\Support;

use App\Models\PageVideo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

/**
 * Resolves the video shown under the hero on a Giving page.
 *
 * config/appeal-videos.php holds the built-in default for every page; an active
 * row in the `page_videos` table (managed from Admin -> Page Videos) overrides
 * the video for that page. This class is the single place that merges the two.
 */
class PageVideos
{
    /**
     * Nicer labels for keys that don't read well when auto-humanised.
     *
     * @var array<string, string>
     */
    protected const LABELS = [
        'zakat-ul-fitr' => 'Zakat ul Fitr',
        'hostel-for-students-orphans' => 'Hostel for Students & Orphans',
        'fidya' => 'Fidya & Kaffarah',
        'dhul-hajj' => 'Dhul Hijjah',
        'sehri-iftar' => 'Sehri & Iftar',
        'eid-gifts' => 'Eid Gifts for Children',
        'cambodia-education-welfare' => 'Cambodia Education & Welfare',
    ];

    /**
     * Every Giving page that has a video section, as `key => human label`.
     * Derived from config/appeal-videos.php so it stays the single source of
     * truth — also feeds the admin page picker and its validation.
     *
     * @return array<string, string>
     */
    public static function pages(): array
    {
        return collect(array_keys((array) config('appeal-videos', [])))
            ->reject(fn ($key) => $key === 'default')
            ->mapWithKeys(fn ($key) => [$key => static::LABELS[$key] ?? Str::headline($key)])
            ->sort()
            ->all();
    }

    /** True when $key is a known Giving page. */
    public static function isPage(string $key): bool
    {
        return array_key_exists($key, static::pages());
    }

    /**
     * The video for a page: the admin override when present and active,
     * otherwise the config default.
     *
     * @return array{url: string, poster: string, title: string}
     */
    public static function resolve(string $key): array
    {
        $base = config('appeal-videos.'.$key, config('appeal-videos.default'));

        if ($override = static::override($key)) {
            $base['url'] = $override->video_url;

            if (filled($override->title)) {
                $base['title'] = $override->title;
            }
        }

        return $base;
    }

    /** True when this page's video is an admin override rather than the default. */
    public static function hasOverride(string $key): bool
    {
        return static::override($key) !== null;
    }

    /**
     * The active override row for a page, or null. Resilient so front-end pages
     * never break before the migration has run on a fresh server — mirrors
     * App\Http\Controllers\HajjController::index().
     */
    public static function override(string $key): ?PageVideo
    {
        try {
            if (Schema::hasTable('page_videos')) {
                return PageVideo::query()
                    ->where('page_key', $key)
                    ->where('is_active', true)
                    ->first();
            }
        } catch (Throwable $e) {
            // fall through
        }

        return null;
    }
}
