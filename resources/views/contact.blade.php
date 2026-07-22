@extends('layouts.app')

@section('title', 'Contact Us — ' . config('app.name'))

@section('content')

    {{-- ===================== CURVED ANIMATED HERO ===================== --}}
    <section class="relative isolate overflow-hidden bg-navy-dark">
        <img src="{{ asset('images/changinslives3.jpg') }}" alt=""
             class="nf-kenburns absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-navy-dark/90 via-navy/80 to-brand/70"></div>

        <div class="nf-float pointer-events-none absolute -right-20 top-10 h-64 w-64 rounded-full bg-brand/30 blur-3xl"></div>
        <div class="nf-float pointer-events-none absolute -left-16 bottom-24 h-56 w-56 rounded-full bg-white/10 blur-3xl" style="animation-delay: 1.5s"></div>

        {{-- Extra top padding clears the centre logo badge that overhangs from the header. --}}
        <div class="relative z-10 nf-container flex min-h-[300px] flex-col items-center justify-center px-4 pb-20 pt-36 text-center sm:pb-24 lg:min-h-[360px] lg:pt-[11.5rem]">
            <span class="nf-in-up inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-white ring-1 ring-white/20">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                We&rsquo;re here to help
            </span>

            <div class="nf-pop mt-6 rounded-2xl bg-white px-8 py-4 shadow-2xl shadow-navy-dark/40 sm:px-12 sm:py-5" style="animation-delay: .1s">
                <h1 class="text-3xl font-extrabold text-navy-dark sm:text-4xl lg:text-5xl">Contact <span class="text-brand">Us</span></h1>
            </div>

            <p class="nf-in-up mt-6 max-w-xl text-base text-white/90 sm:text-lg" style="animation-delay: .25s">
                Have a question, need support, or want to get involved? We&rsquo;d love to hear from you.
            </p>

            <nav class="nf-in-up mt-4 flex items-center justify-center gap-2 text-sm text-white/70" style="animation-delay: .35s">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                <span aria-hidden="true">&rsaquo;</span>
                <span class="text-white">Contact Us</span>
            </nav>
        </div>

        {{-- Animated layered waves along the bottom edge --}}
        <div class="nf-waves" aria-hidden="true">
            <svg class="nf-waves__svg" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="nf-wave" d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>
                <g class="nf-waves__parallax">
                    <use href="#nf-wave" x="48" y="0" fill="rgba(255,255,255,0.7)" />
                    <use href="#nf-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
                    <use href="#nf-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
                    <use href="#nf-wave" x="48" y="7" fill="#ffffff" />
                </g>
            </svg>
        </div>
    </section>

    {{-- ===================== QUICK CONTACT CARDS ===================== --}}
    @php
        $quick = [
            ['title' => 'Call us', 'value' => region('phone'), 'href' => 'tel:'.preg_replace('/[^+0-9]/', '', region('phone')),
             'icon' => '<path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24 11.36 11.36 0 0 0 3.57.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.36 11.36 0 0 0 .57 3.57 1 1 0 0 1-.24 1.02l-2.21 2.2Z"/>'],
            ['title' => 'Email us', 'value' => 'Contact@naeemfoundation.co.uk', 'href' => 'mailto:Contact@naeemfoundation.co.uk',
             'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['title' => 'Office hours', 'value' => 'Mon – Fri · 9am – 6pm', 'href' => null,
             'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>'],
        ];
    @endphp

    <section class="relative z-10 -mt-6 sm:-mt-8">
        <div class="nf-container">
            <div class="grid gap-5 sm:grid-cols-3">
                @foreach ($quick as $i => $card)
                    <div class="nf-reveal rounded-2xl bg-white p-6 text-center shadow-xl ring-1 ring-black/5" data-reveal-delay="{{ $i * 90 }}">
                        <span class="mx-auto grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="{{ $i === 0 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8">{!! $card['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 font-bold text-navy-dark">{{ $card['title'] }}</h3>
                        @if ($card['href'])
                            <a href="{{ $card['href'] }}" class="mt-1 block break-words text-sm text-gray-600 transition-colors hover:text-brand">{{ $card['value'] }}</a>
                        @else
                            <p class="mt-1 text-sm text-gray-600">{{ $card['value'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== FORM + INFO PANEL ===================== --}}
    <section id="form" class="scroll-mt-24 py-14 sm:py-16">
        <div class="nf-container">
            <div class="nf-reveal overflow-hidden rounded-3xl bg-cream shadow-xl ring-1 ring-black/5">
                <div class="grid lg:grid-cols-5">

                    {{-- Left: message form --}}
                    <div class="p-7 sm:p-10 lg:col-span-3">
                        <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Send us a message</h2>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600">
                            Fill in the form below and a member of our team will get back to you as soon as possible.
                        </p>

                        <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-4">
                            @csrf

                            @if ($errors->any())
                                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                                    Please check the highlighted fields and try again.
                                </div>
                            @endif

                            <div class="nf-reveal grid gap-4 sm:grid-cols-2" data-reveal-delay="60">
                                <input name="name" type="text" value="{{ old('name') }}" required placeholder="Full name"
                                       class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30 @error('name') border-red-300 @enderror">
                                <input name="email" type="email" value="{{ old('email') }}" required placeholder="Email address"
                                       class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30 @error('email') border-red-300 @enderror">
                            </div>

                            <div class="nf-reveal grid gap-4 sm:grid-cols-2" data-reveal-delay="140">
                                <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="Phone (optional)"
                                       class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                <input name="subject" type="text" value="{{ old('subject') }}" placeholder="Subject"
                                       class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            </div>

                            <div class="nf-reveal" data-reveal-delay="220">
                                <textarea name="message" rows="5" required placeholder="Your message"
                                          class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30 @error('message') border-red-300 @enderror">{{ old('message') }}</textarea>
                            </div>

                            <div class="nf-reveal" data-reveal-delay="300">
                                <button type="submit" class="nf-cta-pulse btn-brand w-full py-3">
                                    Send message
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>

                            <p class="text-xs text-gray-400">Your details are kept private and used only to respond to your enquiry.</p>
                        </form>
                    </div>

                    {{-- Right: contact info panel --}}
                    <div class="relative overflow-hidden bg-navy-dark p-7 text-white sm:p-10 lg:col-span-2">
                        <div class="nf-float pointer-events-none absolute -right-16 -top-10 h-52 w-52 rounded-full bg-brand/30 blur-3xl"></div>

                        <div class="relative">
                            <h3 class="text-xl font-bold sm:text-2xl">Get in touch</h3>
                            <p class="mt-2 text-sm leading-relaxed text-white/70">
                                Reach us directly through any of the channels below — we aim to reply within one working day.
                            </p>

                            <ul class="mt-7 space-y-6">
                                <li class="nf-reveal flex items-start gap-5">
                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/10 text-white">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24 11.36 11.36 0 0 0 3.57.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.36 11.36 0 0 0 .57 3.57 1 1 0 0 1-.24 1.02l-2.21 2.2Z"/></svg>
                                    </span>
                                    <span>
                                        <span class="block text-xs uppercase tracking-wide text-white/50">Phone</span>
                                        <a href="tel:{{ preg_replace('/[^+0-9]/', '', region('phone')) }}" class="mt-0.5 block font-semibold transition-colors hover:text-brand">{{ region('phone') }}</a>
                                    </span>
                                </li>
                                <li class="nf-reveal flex items-start gap-5">
                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/10 text-white">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-xs uppercase tracking-wide text-white/50">Email</span>
                                        <a href="mailto:Contact@naeemfoundation.co.uk" class="mt-0.5 block break-words font-semibold transition-colors hover:text-brand">Contact@naeemfoundation.co.uk</a>
                                    </span>
                                </li>
                                <li class="nf-reveal flex items-start gap-5">
                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/10 text-white">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11Z" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.5"/></svg>
                                    </span>
                                    <span>
                                        <span class="block text-xs uppercase tracking-wide text-white/50">{{ region('charity_label') }}</span>
                                        <span class="mt-0.5 block font-semibold">{{ region('charity_no') }} &middot; {{ region('name') }}</span>
                                    </span>
                                </li>
                            </ul>

                            <div class="mt-8 border-t border-white/10 pt-6">
                                <p class="text-sm font-semibold text-white/80">Follow us</p>
                                <div class="mt-3 flex items-center gap-3">
                                    <a href="#" aria-label="Facebook" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 transition-colors hover:bg-brand">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13 22v-8h2.6l.4-3H13V9c0-.9.3-1.5 1.6-1.5H16V5c-.3 0-1.3-.1-2.3-.1-2.3 0-3.7 1.3-3.7 3.8V11H8v3h2v8h3Z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Instagram" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 transition-colors hover:bg-brand">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                                    </a>
                                    <a href="#" aria-label="TikTok" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 transition-colors hover:bg-brand">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16 3a5 5 0 0 0 5 5v3a8 8 0 0 1-5-1.8V15a6 6 0 1 1-6-6c.3 0 .7 0 1 .1v3.2A2.8 2.8 0 1 0 13 15V3h3Z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
