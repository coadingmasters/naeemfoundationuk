<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\HeroSlide;
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

        return view('home', compact('heroSlides', 'appeals'));
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

