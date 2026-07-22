@php $user = auth()->user(); @endphp

<header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-gray-200 bg-white/90 px-4 backdrop-blur dark:border-white/10 dark:bg-navy-dark/90 sm:px-6 lg:px-8">
    {{-- Mobile menu button --}}
    <button type="button" data-admin-open class="grid h-10 w-10 place-items-center rounded-md text-navy hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/10 lg:hidden" aria-label="Open menu">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/></svg>
    </button>

    <div class="hidden text-sm text-gray-400 sm:block">
        @yield('title', 'Dashboard')
    </div>

    <div class="ml-auto flex items-center gap-3">
        {{-- Region context: super admin can switch; region admins see a locked badge --}}
        @if (! empty($adminIsSuper))
            <details class="relative">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg border border-gray-200 px-3 py-1.5 text-sm font-semibold text-navy-dark transition hover:bg-gray-50 dark:border-white/10 dark:hover:bg-white/10 [&::-webkit-details-marker]:hidden">
                    <svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.5 6 3.5 9s-1 6.5-3.5 9c-2.5-2.5-3.5-6-3.5-9s1-6.5 3.5-9z"/></svg>
                    <span>{{ $adminIsAll ? 'All regions' : config('countries.list.'.$adminRegion.'.name', $adminRegion) }}</span>
                    <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </summary>
                <div class="absolute right-0 top-full z-30 mt-2 w-56 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl shadow-navy/10 dark:border-white/10">
                    <p class="border-b border-gray-100 px-4 py-2 text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:border-white/10">Manage region</p>
                    <a href="{{ route('admin.region.switch', 'all') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-navy-dark transition hover:bg-cream {{ $adminIsAll ? 'bg-brand/5 text-brand' : '' }}">
                        <span class="text-base">🌍</span> All regions
                        @if ($adminIsAll) <svg class="ml-auto h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg> @endif
                    </a>
                    @foreach ($regions as $r)
                        <a href="{{ route('admin.region.switch', $r['code']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-navy-dark transition hover:bg-cream {{ (! $adminIsAll && $adminRegion === $r['code']) ? 'bg-brand/5 text-brand' : '' }}">
                            <span class="text-base">{{ $r['flag'] }}</span> {{ $r['name'] }}
                            @if (! $adminIsAll && $adminRegion === $r['code']) <svg class="ml-auto h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg> @endif
                        </a>
                    @endforeach
                </div>
            </details>
        @elseif (! empty($adminRegion))
            <span class="inline-flex items-center gap-1.5 rounded-lg bg-brand/10 px-3 py-1.5 text-sm font-semibold text-brand">
                {{ config('countries.list.'.$adminRegion.'.flag', '') }} {{ config('countries.list.'.$adminRegion.'.name', $adminRegion) }}
            </span>
        @endif

        {{-- Dark / light theme toggle --}}
        <button type="button" data-theme-toggle aria-label="Toggle dark mode"
                class="grid h-10 w-10 place-items-center rounded-lg text-navy transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/10">
            <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" stroke-linecap="round"/></svg>
        </button>

        <a href="{{ route('home') }}" target="_blank"
           class="hidden items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-navy transition hover:text-brand sm:inline-flex">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5h5v5m0-5l-8 8M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            View site
        </a>

        {{-- User dropdown --}}
        <div class="relative">
            <button type="button" data-user-btn class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 transition hover:bg-gray-100 dark:hover:bg-white/10">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-brand text-sm font-bold text-white">
                    {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                </span>
                <span class="hidden text-left sm:block">
                    <span class="block text-sm font-semibold leading-tight text-navy-dark">{{ $user->name }}</span>
                    <span class="block text-xs leading-tight text-gray-400">Administrator</span>
                </span>
                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>

            <div data-user-menu class="absolute right-0 top-full mt-2 hidden w-56 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl shadow-navy/10">
                <div class="border-b border-gray-100 px-4 py-3">
                    <p class="text-sm font-semibold text-navy-dark">{{ $user->name }}</p>
                    <p class="truncate text-xs text-gray-400">{{ $user->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 17l5-5-5-5M21 12H9M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
