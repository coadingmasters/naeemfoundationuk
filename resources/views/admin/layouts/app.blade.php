<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }} Admin</title>
    {{-- Apply the saved theme before paint to avoid a flash. --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('nf-admin-theme') === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 antialiased">
<div x-admin class="min-h-screen lg:flex">

    @include('admin.partials.sidebar')

    {{-- Mobile backdrop --}}
    <div data-admin-backdrop class="fixed inset-0 z-30 hidden bg-navy-dark/50 lg:hidden"></div>

    {{-- Main column --}}
    <div class="flex min-h-screen flex-1 flex-col lg:pl-64">
        @include('admin.partials.topbar')

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-6xl">
                {{-- Page heading --}}
                <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h1 class="text-xl font-bold text-navy-dark sm:text-2xl">@yield('heading', 'Dashboard')</h1>
                        @hasSection('subheading')
                            <p class="mt-1 text-sm text-gray-500">@yield('subheading')</p>
                        @endif
                    </div>
                    @yield('actions')
                </div>

                @include('admin.partials.flash')

                @yield('content')
            </div>
        </main>

        @include('admin.partials.footer')
    </div>
</div>

{{-- Shared delete-confirmation popup (used by resource list Delete buttons) --}}
@include('admin.partials.delete-modal')

{{-- "Choose a region" popup — when a super admin adds content in "all regions" mode --}}
@include('admin.partials.region-prompt')

{{-- Animated success/error toasts --}}
@include('admin.partials.toast')

<script>
    (function () {
        const root = document.querySelector('[x-admin]');
        if (!root) return;

        // Mobile sidebar toggle
        const sidebar = root.querySelector('[data-admin-sidebar]');
        const backdrop = root.querySelector('[data-admin-backdrop]');
        const openSidebar = () => { sidebar?.classList.remove('-translate-x-full'); backdrop?.classList.remove('hidden'); };
        const closeSidebar = () => { sidebar?.classList.add('-translate-x-full'); backdrop?.classList.add('hidden'); };

        root.querySelectorAll('[data-admin-open]').forEach((b) => b.addEventListener('click', openSidebar));
        root.querySelectorAll('[data-admin-close]').forEach((b) => b.addEventListener('click', closeSidebar));
        backdrop?.addEventListener('click', closeSidebar);

        // User dropdown
        const userBtn = root.querySelector('[data-user-btn]');
        const userMenu = root.querySelector('[data-user-menu]');
        userBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu?.classList.toggle('hidden');
        });
        document.addEventListener('click', () => userMenu?.classList.add('hidden'));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { userMenu?.classList.add('hidden'); closeSidebar(); } });

        // Dark / light theme toggle (persisted)
        root.querySelectorAll('[data-theme-toggle]').forEach((btn) => btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            try { localStorage.setItem('nf-admin-theme', isDark ? 'dark' : 'light'); } catch (e) {}
        }));
    })();
</script>
@stack('scripts')
</body>
</html>
