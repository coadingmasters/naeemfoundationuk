<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_slides' => HeroSlide::count(),
            'active_slides' => HeroSlide::where('is_active', true)->count(),
            'inactive_slides' => HeroSlide::where('is_active', false)->count(),
        ];

        $recentSlides = HeroSlide::ordered()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSlides'));
    }
}
