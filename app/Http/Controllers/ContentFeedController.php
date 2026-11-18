<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\Cause;
use App\Models\HeroSlide;
use App\Models\NewsPost;
use Illuminate\Http\JsonResponse;

/**
 * A read-only copy of the public home-page content (hero slides, appeals,
 * causes, news) so another install of this site (e.g. naeemfoundation.ca) can
 * load the same cards with `php artisan content:import`.
 *
 * Only active rows and only what is already shown on the public pages — no
 * personal data, nothing from the admin.
 */
class ContentFeedController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $own = config('countries.content_region', 'GB');

        $region = fn (string $model) => $model::query()->forRegion($own)->active()->ordered();

        return response()->json([
            'region' => $own,
            'hero_slides' => $region(HeroSlide::class)->get()
                ->map->only(['title', 'subtitle', 'image', 'mobile_image', 'image_position', 'button_text', 'button_url', 'sort_order'])->values(),
            'appeals' => $region(Appeal::class)->get()
                ->map->only(['title', 'slug', 'description', 'image', 'link', 'sort_order'])->values(),
            'causes' => $region(Cause::class)->get()
                ->map->only(['title', 'description', 'image', 'link', 'sort_order'])->values(),
            'news_posts' => NewsPost::query()->active()->ordered()->get()
                ->map(fn ($p) => $p->only(['title', 'slug', 'category', 'excerpt', 'body', 'image', 'is_featured'])
                    + ['published_at' => optional($p->published_at)->toDateString()])->values(),
        ])->header('Cache-Control', 'public, max-age=300');
    }
}
