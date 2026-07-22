<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Active region drives client-side currency + phone defaults (read by app.js). --}}
<body class="antialiased" data-currency="{{ region('symbol', '£') }}" data-region="{{ strtolower(region('code', 'GB')) }}">
    {{-- Pages with a light hero opt into a solid header via @section('header-solid', 'yes'). --}}
    @include('partials.header', ['solid' => trim($__env->yieldContent('header-solid')) === 'yes'])

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- First-visit region / currency chooser (UK · US · Canada) --}}
    @include('partials.country-popup')

    {{-- Cookie consent (shows once until the visitor chooses) --}}
    @include('partials.cookie-consent')

    {{-- Scroll-to-top with circular scroll-progress ring (appears on every page) --}}
    <button type="button" id="scrollTop" class="nf-scrolltop" aria-label="Scroll to top">
        <svg class="nf-scrolltop__ring" viewBox="0 0 48 48" aria-hidden="true">
            <circle class="nf-scrolltop__track" cx="24" cy="24" r="21"></circle>
            <circle class="nf-scrolltop__progress" cx="24" cy="24" r="21"></circle>
        </svg>
        <svg class="nf-scrolltop__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
            <path d="M6 14l6-6 6 6" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>

    {{-- Global success popup — auto-opens after any form that flashes session('success') --}}
    @if (session('success'))
        <div class="nf-modal" data-success-modal hidden>
            <div class="nf-modal__backdrop" data-success-close></div>
            <div class="nf-modal__card text-center" role="dialog" aria-modal="true" aria-label="Success">
                <button type="button" class="nf-modal__close" data-success-close aria-label="Close">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                </button>
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-green-100 text-green-600">
                    <svg class="nf-success-check h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <h3 class="mt-5 text-xl font-bold text-navy-dark">Thank you!</h3>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ session('success') }}</p>
                <button type="button" data-success-close class="mt-6 w-full rounded-lg bg-brand px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-dark">Done</button>
            </div>
        </div>
        <script>
            (function () {
                const m = document.querySelector('[data-success-modal]');
                if (!m) return;
                const open = () => { m.hidden = false; requestAnimationFrame(() => m.classList.add('is-open')); };
                const close = () => { m.classList.remove('is-open'); setTimeout(() => m.remove(), 340); };
                m.querySelectorAll('[data-success-close]').forEach((el) => el.addEventListener('click', close));
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !m.hidden) close(); });
                setTimeout(open, 150);
            })();
        </script>
    @endif

    @stack('scripts')
</body>
</html>
