@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Overview of your website content and activity.')

@php
    $toneHex = [
        'brand' => '#740a2e', 'navy' => '#183b4f', 'emerald' => '#059669',
        'amber' => '#d97706', 'rose' => '#e11d48', 'sky' => '#0284c7',
    ];
    $toneBadge = [
        'brand' => 'bg-brand/10 text-brand', 'navy' => 'bg-navy/10 text-navy',
        'emerald' => 'bg-emerald-100 text-emerald-600', 'amber' => 'bg-amber-100 text-amber-600',
        'rose' => 'bg-rose-100 text-rose-600', 'sky' => 'bg-sky-100 text-sky-600',
    ];
    $chartMax = max(1, max(array_column($content, 'total')) ?: 1);
    $livePct = $totals['items'] ? $totals['live'] / $totals['items'] : 0;
    $circ = 2 * M_PI * 52;
@endphp

@section('content')

    {{-- ===== Welcome banner ===== --}}
    <div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-navy-dark via-navy to-brand p-6 text-white shadow-lg sm:p-8">
        <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-brand/30 blur-2xl"></div>
        <div class="relative flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="text-sm text-white/70">{{ now()->format('l, j F Y') }}</p>
                <h2 class="mt-1 text-2xl font-extrabold sm:text-3xl">Welcome back, {{ auth()->user()->name }} <span class="inline-block">👋</span></h2>
                <p class="mt-1 text-sm text-white/75">Here's what's currently published across your website.</p>
            </div>
            <div class="flex gap-3">
                <div class="rounded-xl bg-white/10 px-4 py-3 text-center ring-1 ring-white/15">
                    <p class="text-2xl font-extrabold" data-countup data-value="{{ $totals['items'] }}">0</p>
                    <p class="text-[11px] uppercase tracking-wide text-white/60">Total items</p>
                </div>
                <div class="rounded-xl bg-white/10 px-4 py-3 text-center ring-1 ring-white/15">
                    <p class="text-2xl font-extrabold text-green-300" data-countup data-value="{{ $totals['live'] }}">0</p>
                    <p class="text-[11px] uppercase tracking-wide text-white/60">Live</p>
                </div>
                <div class="rounded-xl bg-white/10 px-4 py-3 text-center ring-1 ring-white/15">
                    <p class="text-2xl font-extrabold text-white/80" data-countup data-value="{{ $totals['hidden'] }}">0</p>
                    <p class="text-[11px] uppercase tracking-wide text-white/60">Hidden</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Super-admin per-region breakdown (only in "all regions" mode) ===== --}}
    @if (! empty($regionBreakdown))
        <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-400">By region</h3>
        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            @foreach ($regionBreakdown as $rb)
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl leading-none">{{ $rb['flag'] }}</span>
                        <span class="font-bold text-navy-dark">{{ $rb['name'] }}</span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div><p class="text-lg font-extrabold text-navy-dark">{{ $rb['live'] }}</p><p class="text-xs text-gray-500">Live items</p></div>
                        <div><p class="text-lg font-extrabold text-navy-dark">{{ $rb['symbol'] }}{{ number_format($rb['revenue'], 2) }}</p><p class="text-xs text-gray-500">Revenue</p></div>
                        <div><p class="text-lg font-extrabold text-navy-dark">{{ $rb['orders'] }}</p><p class="text-xs text-gray-500">Orders</p></div>
                        <div><p class="text-lg font-extrabold text-navy-dark">{{ $rb['donations'] }}</p><p class="text-xs text-gray-500">Donations</p></div>
                        <div><p class="text-lg font-extrabold text-navy-dark">{{ $rb['questions'] }}</p><p class="text-xs text-gray-500">Mufti Q&amp;A</p></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ===== Engagement KPIs ===== --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="nf-anim rounded-2xl border border-gray-100 bg-white p-5 shadow-sm" style="animation-delay:0ms">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand/10 text-brand">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand hover:underline">View</a>
            </div>
            <p class="mt-4 text-2xl font-extrabold text-navy-dark" data-countup data-value="{{ $revenue }}" data-money="1">£0.00</p>
            <p class="text-xs font-medium text-gray-500">Shop revenue</p>
        </div>
        @foreach ($engagement as $i => $e)
            <a href="{{ $e['route'] }}" class="nf-anim group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" style="animation-delay:{{ ($i + 1) * 70 }}ms">
                <div class="flex items-center justify-between">
                    <span class="grid h-11 w-11 place-items-center rounded-xl {{ $toneBadge[$e['tone']] ?? 'bg-brand/10 text-brand' }}">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $e['icon'] !!}</svg>
                    </span>
                    <svg class="h-4 w-4 text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <p class="mt-4 text-2xl font-extrabold text-navy-dark" data-countup data-value="{{ $e['value'] }}">0</p>
                <p class="text-xs font-medium text-gray-500">{{ $e['label'] }} <span class="text-gray-400">· {{ $e['sub'] }}</span></p>
            </a>
        @endforeach
    </div>

    {{-- ===== Charts ===== --}}
    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        {{-- Bar chart --}}
        <div class="nf-anim rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2" style="animation-delay:0ms">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-sm font-bold text-navy-dark">Content by section</h3>
                <span class="text-xs text-gray-400">Items per section</span>
            </div>
            <div class="flex items-end gap-3 sm:gap-5">
                @foreach ($content as $s)
                    @php $h = round(($s['total'] / $chartMax) * 100); @endphp
                    <div class="flex flex-1 flex-col items-center">
                        <span data-countup data-value="{{ $s['total'] }}" class="mb-1.5 text-sm font-bold text-navy-dark">0</span>
                        <div class="flex h-32 w-full items-end">
                            <div class="nf-bar mx-auto w-full max-w-[2.75rem]" data-bar-h="{{ $h }}" style="background-color: {{ $toneHex[$s['tone']] ?? '#740a2e' }}"></div>
                        </div>
                        <span class="mt-2 text-center text-[10px] font-medium leading-tight text-gray-500">{{ $s['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Donut --}}
        <div class="nf-anim rounded-2xl border border-gray-100 bg-white p-6 shadow-sm" style="animation-delay:90ms">
            <h3 class="text-sm font-bold text-navy-dark">Live vs Hidden</h3>
            <div class="mt-4 flex flex-col items-center">
                <div class="relative">
                    <svg viewBox="0 0 120 120" class="h-40 w-40 -rotate-90">
                        <circle cx="60" cy="60" r="52" fill="none" stroke-width="12" stroke="currentColor" class="text-gray-100 dark:text-white/10"/>
                        <circle cx="60" cy="60" r="52" fill="none" stroke-width="12" stroke="#16a34a" stroke-linecap="round"
                                class="nf-donut__val" data-donut="{{ $livePct }}"
                                stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $circ }}"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-extrabold text-navy-dark" data-countup data-value="{{ round($livePct * 100) }}" data-suffix="%">0%</span>
                        <span class="text-[11px] text-gray-400">Live</span>
                    </div>
                </div>
                <div class="mt-4 flex w-full items-center justify-around text-xs">
                    <span class="inline-flex items-center gap-1.5 font-medium text-navy-dark"><span class="h-2.5 w-2.5 rounded-full bg-green-600"></span> {{ $totals['live'] }} live</span>
                    <span class="inline-flex items-center gap-1.5 font-medium text-gray-500"><span class="h-2.5 w-2.5 rounded-full bg-gray-300"></span> {{ $totals['hidden'] }} hidden</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Content sections ===== --}}
    <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-400">Manage content</h3>
    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($content as $i => $s)
            <div class="nf-anim rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md" style="animation-delay:{{ $i * 60 }}ms">
                <div class="flex items-center justify-between">
                    <span class="grid h-11 w-11 place-items-center rounded-xl {{ $toneBadge[$s['tone']] ?? 'bg-brand/10 text-brand' }}">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $s['icon'] !!}</svg>
                    </span>
                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-[11px] font-semibold text-green-700">{{ $s['active'] }} live</span>
                </div>
                <p class="mt-4 text-3xl font-extrabold text-navy-dark" data-countup data-value="{{ $s['total'] }}">0</p>
                <p class="text-sm text-gray-500">{{ $s['label'] }}</p>
                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3 text-sm">
                    <a href="{{ $s['index'] }}" class="font-semibold text-brand hover:underline">Manage</a>
                    <a href="{{ $s['create'] }}" class="inline-flex items-center gap-1 font-medium text-navy hover:text-brand">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                        Add
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== Recent activity ===== --}}
    @if ($recent->isNotEmpty())
        <div class="nf-anim rounded-2xl border border-gray-100 bg-white shadow-sm" style="animation-delay:0ms">
            <div class="border-b border-gray-100 px-5 py-4">
                <h3 class="text-sm font-bold text-navy-dark">Recently added</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach ($recent as $item)
                    <li>
                        <a href="{{ $item['edit_url'] }}" class="flex items-center gap-4 px-5 py-3 transition hover:bg-gray-50">
                            @if ($item['image'])
                                <img src="{{ asset($item['image']) }}" alt="" class="h-11 w-11 shrink-0 rounded-lg object-cover ring-1 ring-gray-200">
                            @else
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-lg bg-cream text-brand">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="5" width="18" height="14" rx="2"/></svg>
                                </span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-navy-dark">{{ $item['title'] ?: 'Untitled' }}</p>
                                <p class="text-xs text-gray-400">{{ $item['type'] }} · {{ $item['time']?->diffForHumans() }}</p>
                            </div>
                            <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $item['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $item['is_active'] ? 'Live' : 'Hidden' }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection

@push('scripts')
<script>
(function () {
    // Count-up numbers
    document.querySelectorAll('[data-countup]').forEach((el) => {
        const target = parseFloat(el.dataset.value) || 0;
        const isMoney = el.dataset.money === '1';
        const suffix = el.dataset.suffix || '';
        const dur = 950;
        const start = performance.now();
        const step = (now) => {
            const p = Math.min(1, (now - start) / dur);
            const eased = 1 - Math.pow(1 - p, 3);
            const val = target * eased;
            el.textContent = isMoney ? '£' + val.toFixed(2) : Math.round(val).toLocaleString() + suffix;
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    });

    // Grow the bars
    requestAnimationFrame(() => {
        document.querySelectorAll('.nf-bar').forEach((b) => { b.style.height = (b.dataset.barH || 0) + '%'; });
    });

    // Draw the donut
    document.querySelectorAll('.nf-donut__val').forEach((c) => {
        const circ = parseFloat(c.getAttribute('stroke-dasharray')) || 0;
        const pct = parseFloat(c.dataset.donut) || 0;
        requestAnimationFrame(() => { c.style.strokeDashoffset = String(circ * (1 - pct)); });
    });
})();
</script>
@endpush
