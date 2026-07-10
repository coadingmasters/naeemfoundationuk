@extends('layouts.app')

@section('title', 'Ask a Mufti — ' . config('app.name'))

@section('content')

    {{-- ===================== CURVED ANIMATED HERO ===================== --}}
    <section class="relative isolate overflow-hidden bg-navy-dark">
        {{-- Background image with a slow Ken Burns zoom --}}
        <img src="{{ asset('images/homepagehero.png') }}" alt=""
             class="nf-kenburns absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-navy-dark/90 via-navy/80 to-brand/70"></div>

        {{-- Floating decorative glows --}}
        <div class="nf-float pointer-events-none absolute -right-20 top-10 h-64 w-64 rounded-full bg-brand/30 blur-3xl"></div>
        <div class="nf-float pointer-events-none absolute -left-16 bottom-24 h-56 w-56 rounded-full bg-white/10 blur-3xl" style="animation-delay: 1.5s"></div>

        <div class="relative z-10 nf-container flex min-h-[460px] flex-col items-center justify-center px-4 pb-48 pt-20 text-center sm:min-h-[540px] sm:pb-56 sm:pt-24">
            <span class="nf-in-up inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-white ring-1 ring-white/20">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                Guidance &amp; Fatwa
            </span>

            <div class="nf-pop mt-6 rounded-2xl bg-white px-8 py-4 shadow-2xl shadow-navy-dark/40 sm:px-12 sm:py-5" style="animation-delay: .1s">
                <h1 class="text-3xl font-extrabold text-navy-dark sm:text-4xl lg:text-5xl">Ask a <span class="text-brand">Mufti</span></h1>
            </div>

            <p class="nf-in-up mt-6 max-w-xl text-base text-white/90 sm:text-lg" style="animation-delay: .25s">
                Connect with Us for Positive Change and Impact
            </p>

            <nav class="nf-in-up mt-4 flex items-center justify-center gap-2 text-sm text-white/70" style="animation-delay: .35s">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                <span>›</span>
                <span class="text-white">Ask a Mufti</span>
            </nav>
        </div>

        {{-- Curved bottom edge --}}
        <svg class="absolute inset-x-0 bottom-0 h-[60px] w-full sm:h-[100px]" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
            <path fill="#ffffff" d="M0,64 C360,140 1080,140 1440,64 L1440,120 L0,120 Z"></path>
        </svg>
    </section>

    {{-- ===================== FORM CARD (overlapping) ===================== --}}
    <section id="form" class="relative z-10 -mt-40 scroll-mt-24 pb-16 sm:-mt-48 sm:pb-20">
        <div class="nf-container">
            <div class="nf-reveal overflow-hidden rounded-3xl bg-cream shadow-xl ring-1 ring-black/5">
                <div class="grid lg:grid-cols-2">
                    {{-- Left: intro + form --}}
                    <div class="p-7 sm:p-10">
                        <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">How can we help?</h2>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600">
                            Welcome to the ‘Ask a Mufti’ section — a dedicated space for addressing your Islamic concerns
                            and questions. Understanding the teachings of Islam and applying them to modern life can be
                            challenging, and we are here to offer guidance and insights with care and confidentiality.
                        </p>

                        @if (session('success'))
                            <div class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('ask-mufti.store') }}" class="mt-6 space-y-4">
                            @csrf

                            @if ($errors->any())
                                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                                    Please check the highlighted fields and try again.
                                </div>
                            @endif

                            <div>
                                <input name="name" type="text" value="{{ old('name') }}" required placeholder="Name"
                                       class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30 @error('name') border-red-300 @enderror">
                            </div>
                            <div>
                                <input name="email" type="email" value="{{ old('email') }}" required placeholder="Email address"
                                       class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30 @error('email') border-red-300 @enderror">
                            </div>
                            <div>
                                <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="Phone"
                                       class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            </div>
                            <div>
                                <textarea name="message" rows="5" required placeholder="Message"
                                          class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30 @error('message') border-red-300 @enderror">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="btn-brand w-full py-3">
                                Submit
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            <p class="text-xs text-gray-400">Your details are kept private and used only to respond to your question.</p>
                        </form>
                    </div>

                    {{-- Right: image --}}
                    <div class="relative min-h-[280px] lg:min-h-full">
                        <img src="{{ asset('images/zakathero.png') }}" alt="Seeking Islamic guidance"
                             class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-dark/30 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== GET INVOLVED CTA ===================== --}}
    <section class="pb-20">
        <div class="nf-container">
            <div class="nf-reveal mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Get involved</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Join thousands of others across the country</h2>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    Giving your time means helping to give those most vulnerable a chance at the future they deserve.
                </p>
                <a href="{{ route('donate.checkout') }}" class="btn-brand mt-7 px-7 py-3">
                    Make a donation
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

@endsection
