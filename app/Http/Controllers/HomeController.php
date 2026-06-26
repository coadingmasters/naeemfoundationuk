<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        // Resilient: if the database/table isn't ready yet (e.g. migrations
        // haven't run on the server), fall back to the view's default slide
        // instead of breaking the live homepage.
        $heroSlides = collect();

        try {
            if (Schema::hasTable('hero_slides')) {
                $heroSlides = HeroSlide::active()->ordered()->get();
            }
        } catch (Throwable $e) {
            $heroSlides = collect();
        }

        return view('home', compact('heroSlides'));
    }
}

