<?php

namespace App\Support;

use App\Models\PageHero;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

/**
 * Resolves the hero background photo for a Giving page.
 *
 * Every page that renders partials/donate-hero.blade.php (or zakat.blade.php,
 * which mirrors it) hardcodes a default `heroImage`. An active row in the
 * `page_heroes` table — managed from Admin -> Hero Banners — overrides that
 * default for the matching page (identified by its route name). No code
 * change is needed to update a hero photo once this is wired in.
 */
class PageHeroes
{
    /**
     * Every page with a hero banner, as `route name => human label`.
     * Reuses App\Support\PageVideos' labels (the two page sets overlap almost
     * entirely) so the naming stays consistent across both admin screens.
     *
     * @return array<string, string>
     */
    public static function pages(): array
    {
        $keys = [
            'zakat', 'zakat-ul-fitr', 'fidya', 'sadaqah', 'sadaqah-jariyah', 'sehri-iftar',
            'water-well', 'healthcare', 'food-sustenance', 'food-appeal', 'sustainable-livelihood',
            'cambodia-education-welfare', 'education-sponsorships', 'hostel-for-students-orphans',
            'clean-water', 'widows', 'lillah', 'aqiqah', 'kaffarah', 'dhul-hajj', 'qurbani',
            'prosthetic-limb', 'eid-gifts', 'ramadan-food-packs', 'hajj', 'volunteer',
        ];

        $labels = PageVideos::pages();

        return collect($keys)
            ->mapWithKeys(fn ($key) => [$key => $labels[$key] ?? Str::headline($key)])
            ->sort()
            ->all();
    }

    /** True when $key is a known hero-banner page. */
    public static function isPage(string $key): bool
    {
        return array_key_exists($key, static::pages());
    }

    /**
     * The hero image for a page: the admin upload when present and active,
     * otherwise the page's own hardcoded default.
     */
    public static function resolve(string $key, string $default): string
    {
        $override = static::override($key);

        return $override ? $override->image : $default;
    }

    /** True when this page's hero is an admin upload rather than the default. */
    public static function hasOverride(string $key): bool
    {
        return static::override($key) !== null;
    }

    /**
     * The phone-specific hero photo for a page, or null when the page has no
     * custom banner or no mobile-specific photo was uploaded for it — callers
     * should fall back to resolve()'s desktop photo in that case.
     */
    public static function resolveMobile(string $key): ?string
    {
        return static::override($key)?->mobile_image;
    }

    /**
     * The active override row for a page, or null. Resilient so front-end pages
     * never break before the migration has run on a fresh server.
     */
    public static function override(string $key): ?PageHero
    {
        if ($key === '') {
            return null;
        }

        try {
            if (Schema::hasTable('page_heroes')) {
                return PageHero::query()
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
