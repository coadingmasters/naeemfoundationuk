<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-cream antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">

        {{-- Brand panel --}}
        <div class="relative hidden overflow-hidden bg-gradient-to-br from-navy via-navy-dark to-brand lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-brand/30 blur-3xl"></div>

            <div class="relative z-10 p-10">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-16 w-auto brightness-0 invert">
            </div>

            <div class="relative z-10 p-10 text-white">
                <h2 class="max-w-md text-3xl font-extrabold leading-tight">Admin Control Center</h2>
                <p class="mt-3 max-w-md text-sm leading-relaxed text-white/80">
                    Manage your homepage hero slider, content and campaigns — all from one professional, secure dashboard.
                </p>
                <div class="mt-8 flex items-center gap-3 text-xs text-white/70">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    Secure, role-protected access.
                </div>
            </div>
        </div>

        {{-- Form panel --}}
        <div class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md">
                {{-- Mobile logo --}}
                <div class="mb-8 flex justify-center lg:hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-14 w-auto">
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-xl shadow-navy/5">
                    <h1 class="text-2xl font-bold text-navy-dark">Welcome back</h1>
                    <p class="mt-1 text-sm text-gray-500">Sign in to your administrator account.</p>

                    @if ($errors->any())
                        <div class="mt-5 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v5m0 3h.01M10.3 3.86l-7.6 13A1.5 1.5 0 0 0 4 19h16a1.5 1.5 0 0 0 1.3-2.14l-7.6-13a1.5 1.5 0 0 0-2.6 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-semibold text-navy-dark">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                   placeholder="admin@naeemfoundation.org"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-semibold text-navy-dark">Password</label>
                            <input id="password" name="password" type="password" required
                                   placeholder="••••••••"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                        </div>

                        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-brand">
                            Keep me signed in
                        </label>

                        <button type="submit"
                                class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition-colors hover:bg-brand-dark">
                            Sign In
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>

                <p class="mt-6 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
                <p class="mt-2 text-center">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-navy hover:text-brand">&larr; Back to website</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
