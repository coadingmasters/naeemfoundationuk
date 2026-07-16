<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    {{-- Pages with a light hero opt into a solid header via @section('header-solid', 'yes'). --}}
    @include('partials.header', ['solid' => trim($__env->yieldContent('header-solid')) === 'yes'])

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

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

    @stack('scripts')
</body>
</html>
