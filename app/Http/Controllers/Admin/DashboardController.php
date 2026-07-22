<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Cause;
use App\Models\Donation;
use App\Models\HajjRegistration;
use App\Models\HajjVideo;
use App\Models\HeroSlide;
use App\Models\Order;
use App\Models\Product;
use App\Models\Project;
use App\Models\Volunteer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $content = [
            $this->section('Hero Slides', HeroSlide::class, 'admin.hero-slides', 'brand',
                '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 15l5-4 4 3 3-2 6 5" stroke-linecap="round" stroke-linejoin="round"/>'),
            $this->section('Appeals', Appeal::class, 'admin.appeals', 'navy',
                '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'),
            $this->section('Causes', Cause::class, 'admin.causes', 'emerald',
                '<path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" stroke-linecap="round" stroke-linejoin="round"/>'),
            $this->section('Projects', Project::class, 'admin.projects', 'amber',
                '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke-linecap="round" stroke-linejoin="round"/>'),
            $this->section('Products', Product::class, 'admin.products', 'rose',
                '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/>'),
            $this->section('Hajj Videos', HajjVideo::class, 'admin.hajj-videos', 'sky',
                '<path d="M15 10l4.55-2.28A1 1 0 0 1 21 8.62v6.76a1 1 0 0 1-1.45.9L15 14M4 6h9a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke-linecap="round" stroke-linejoin="round"/>'),
        ];

        $totals = [
            'items' => array_sum(array_column($content, 'total')),
            'live' => array_sum(array_column($content, 'active')),
            'hidden' => array_sum(array_column($content, 'total')) - array_sum(array_column($content, 'active')),
        ];

        $revenue = (float) Order::whereIn('status', ['pending', 'processing', 'completed'])->sum('subtotal');

        $engagement = [
            [
                'label' => 'Shop Orders', 'value' => Order::count(), 'route' => route('admin.orders.index'), 'tone' => 'brand',
                'sub' => Order::where('status', 'pending')->count().' pending',
                'icon' => '<path d="M4 4h12l4 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z" stroke-linejoin="round"/><path d="M8 9h8M8 13h8" stroke-linecap="round"/>',
            ],
            [
                'label' => 'Volunteers', 'value' => Volunteer::count(), 'route' => route('admin.volunteers.index'), 'tone' => 'emerald',
                'sub' => 'sign-ups',
                'icon' => '<circle cx="9" cy="8" r="3"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0" stroke-linecap="round"/><path d="M16 3.2a3 3 0 0 1 0 5.6M17.5 20a5.5 5.5 0 0 0-2.7-4.8" stroke-linecap="round"/>',
            ],
            [
                'label' => 'Hajj Registrations', 'value' => HajjRegistration::count(), 'route' => route('admin.hajj-registrations.index'), 'tone' => 'sky',
                'sub' => 'registrations',
                'icon' => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1M9 10h6M9 14h6" stroke-linecap="round" stroke-linejoin="round"/>',
            ],
        ];

        $recent = $this->recentFrom(HeroSlide::class, 'Hero Slide', 'admin.hero-slides')
            ->concat($this->recentFrom(Appeal::class, 'Appeal', 'admin.appeals'))
            ->concat($this->recentFrom(Cause::class, 'Cause', 'admin.causes'))
            ->concat($this->recentFrom(Project::class, 'Project', 'admin.projects'))
            ->sortByDesc('time')
            ->take(6)
            ->values();

        // Super admins viewing "all regions" get a per-region oversight breakdown.
        $regionBreakdown = null;
        if (auth()->user()?->isSuperAdmin() && \App\Support\RegionContext::isAll()) {
            $regionBreakdown = collect(config('countries.list'))->map(fn ($r, $code) => [
                'name' => $r['name'],
                'flag' => $r['flag'],
                'symbol' => $r['symbol'],
                'live' => HeroSlide::forRegion($code)->where('is_active', true)->count()
                    + Appeal::forRegion($code)->where('is_active', true)->count()
                    + Cause::forRegion($code)->where('is_active', true)->count()
                    + Project::forRegion($code)->where('is_active', true)->count()
                    + Product::forRegion($code)->where('is_active', true)->count()
                    + HajjVideo::forRegion($code)->where('is_active', true)->count(),
                'orders' => Order::forRegion($code)->count(),
                'donations' => Donation::forRegion($code)->count(),
                'revenue' => (float) Order::forRegion($code)->whereIn('status', ['pending', 'processing', 'completed'])->sum('subtotal'),
            ])->values();
        }

        return view('admin.dashboard', compact('content', 'totals', 'engagement', 'revenue', 'recent', 'regionBreakdown'));
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
