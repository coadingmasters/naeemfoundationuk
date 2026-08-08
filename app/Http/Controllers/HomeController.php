<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\Cause;
use App\Models\HeroSlide;
use App\Models\NewsPost;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        // Resilient: if the database/tables aren't ready yet (e.g. migrations
        // haven't run on the server), fall back to the view's defaults instead
        // of breaking the live homepage.
        $heroSlides = $this->safeFetch('hero_slides', fn () => HeroSlide::active()->ordered()->get());
        $appeals = $this->safeFetch('appeals', fn () => Appeal::active()->ordered()->get());
        $causes = $this->safeFetch('causes', fn () => Cause::active()->ordered()->get());
        // Real articles for the "Latest News" strip, so each card can link to
        // its own post. The view falls back to placeholders when none exist.
        $newsPosts = $this->safeFetch('news_posts', fn () => NewsPost::active()->ordered()->take(3)->get());

        return view('home', compact('heroSlides', 'appeals', 'causes', 'newsPosts'));
    }

    /** Query a table only if it exists, swallowing connection errors. */
    private function safeFetch(string $table, callable $query)
    {
        try {
            return Schema::hasTable($table) ? $query() : collect();
        } catch (Throwable $e) {
            return collect();
        }
    }
}

