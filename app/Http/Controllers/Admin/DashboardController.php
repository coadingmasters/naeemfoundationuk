<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Cause;
use App\Models\HeroSlide;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $sections = [
            $this->section('Hero Slides', HeroSlide::class, 'admin.hero-slides', 'brand',
                '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 15l5-4 4 3 3-2 6 5" stroke-linecap="round" stroke-linejoin="round"/>'),
            $this->section('Appeals', Appeal::class, 'admin.appeals', 'navy',
                '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'),
            $this->section('Causes', Cause::class, 'admin.causes', 'emerald',
                '<path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" stroke-linecap="round" stroke-linejoin="round"/>'),
            $this->section('Projects', Project::class, 'admin.projects', 'amber',
                '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke-linecap="round" stroke-linejoin="round"/>'),
        ];

        $totals = [
            'items' => array_sum(array_column($sections, 'total')),
            'live' => array_sum(array_column($sections, 'active')),
            'hidden' => array_sum(array_column($sections, 'total')) - array_sum(array_column($sections, 'active')),
        ];

        $recent = $this->recentFrom(HeroSlide::class, 'Hero Slide', 'admin.hero-slides')
            ->concat($this->recentFrom(Appeal::class, 'Appeal', 'admin.appeals'))
            ->concat($this->recentFrom(Cause::class, 'Cause', 'admin.causes'))
            ->concat($this->recentFrom(Project::class, 'Project', 'admin.projects'))
            ->sortByDesc('time')
            ->take(6)
            ->values();

        return view('admin.dashboard', compact('sections', 'totals', 'recent'));
    }

    private function section(string $label, string $model, string $routePrefix, string $tone, string $icon): array
    {
        return [
            'label' => $label,
            'total' => $model::count(),
            'active' => $model::where('is_active', true)->count(),
            'index' => route($routePrefix.'.index'),
            'create' => route($routePrefix.'.create'),
            'tone' => $tone,
            'icon' => $icon,
        ];
    }

    private function recentFrom(string $model, string $type, string $routePrefix): Collection
    {
        return $model::latest()->take(6)->get()->map(fn ($m) => [
            'type' => $type,
            'title' => Str::limit(trim(str_replace("\n", ' ', $m->title)), 42),
            'image' => $m->image,
            'is_active' => (bool) $m->is_active,
            'time' => $m->created_at,
            'edit_url' => route($routePrefix.'.edit', $m),
        ]);
    }
}
