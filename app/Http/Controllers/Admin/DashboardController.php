<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\HeroSlide;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_slides' => HeroSlide::count(),
            'active_slides' => HeroSlide::where('is_active', true)->count(),
            'total_appeals' => Appeal::count(),
            'active_appeals' => Appeal::where('is_active', true)->count(),
        ];

        $recentSlides = HeroSlide::ordered()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSlides'));
    }
}
