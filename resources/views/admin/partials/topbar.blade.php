@php $user = auth()->user(); @endphp

<header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-gray-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8">
    {{-- Mobile menu button --}}
    <button type="button" data-admin-open class="grid h-10 w-10 place-items-center rounded-md text-navy hover:bg-gray-100 lg:hidden" aria-label="Open menu">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/></svg>
    </button>

    <div class="hidden text-sm text-gray-400 sm:block">
        @yield('title', 'Dashboard')
    </div>

    <div class="ml-auto flex items-center gap-3">
        <a href="{{ route('home') }}" target="_blank"
           class="hidden items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-navy transition hover:text-brand sm:inline-flex">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5h5v5m0-5l-8 8M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            View site
        </a>

        {{-- User dropdown --}}
        <div class="relative">
            <button type="button" data-user-btn class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 transition hover:bg-gray-100">
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
