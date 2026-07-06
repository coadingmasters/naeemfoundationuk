@php
    $nav = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => request()->routeIs('admin.dashboard'),
            'icon' => '<path d="M4 13h6V4H4v9zm0 7h6v-5H4v5zm10 0h6v-9h-6v9zm0-16v5h6V4h-6z" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label' => 'Hero Slides',
            'route' => 'admin.hero-slides.index',
            'active' => request()->routeIs('admin.hero-slides.*'),
            'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 15l5-4 4 3 3-2 6 5" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label' => 'Latest Appeals',
            'route' => 'admin.appeals.index',
            'active' => request()->routeIs('admin.appeals.*'),
            'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label' => 'Causes',
            'route' => 'admin.causes.index',
            'active' => request()->routeIs('admin.causes.*'),
            'icon' => '<path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label' => 'Projects',
            'route' => 'admin.projects.index',
            'active' => request()->routeIs('admin.projects.*'),
            'icon' => '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'label' => 'Hajj Videos',
            'route' => 'admin.hajj-videos.index',
            'active' => request()->routeIs('admin.hajj-videos.*'),
            'icon' => '<path d="M15 10l4.55-2.28A1 1 0 0 1 21 8.62v6.76a1 1 0 0 1-1.45.9L15 14M4 6h9a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
    ];
@endphp

<aside data-admin-sidebar
       class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform border-r border-navy-dark/40 bg-navy text-white transition-transform duration-300 lg:translate-x-0">
    <div class="flex h-full flex-col">

        {{-- Brand --}}
        <div class="flex h-20 items-center justify-between gap-2 border-b border-white/10 px-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto brightness-0 invert">
                <span class="text-sm font-bold leading-tight">Admin<br><span class="text-white/60 font-normal">Panel</span></span>
            </a>
            <button type="button" data-admin-close class="grid h-9 w-9 place-items-center rounded-md text-white/70 hover:bg-white/10 lg:hidden" aria-label="Close menu">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-white/40">Manage</p>
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ $item['active'] ? 'bg-brand text-white shadow-lg shadow-brand/30' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $item['icon'] !!}</svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Quick action --}}
        <div class="px-3 pb-3">
            <a href="{{ route('admin.hero-slides.create') }}"
               class="flex w-full items-center justify-center gap-2 rounded-lg bg-white/10 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                New Slide
            </a>
        </div>

        {{-- View site --}}
        <div class="border-t border-white/10 px-3 py-3">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-white/70 transition hover:bg-white/10 hover:text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 5h5v5m0-5l-8 8M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                View Website
            </a>
        </div>
    </div>
</aside>
