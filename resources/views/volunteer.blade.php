@extends('layouts.app')

@section('title', 'Volunteer — ' . config('app.name'))

@section('content')

    {{-- ===================== ANIMATED HERO ===================== --}}
    <section class="relative isolate overflow-hidden bg-navy-dark">
        <img src="{{ asset('images/voluntear.png') }}" alt=""
             class="nf-kenburns absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-navy-dark/90 via-navy/80 to-brand/70"></div>

        <div class="nf-float pointer-events-none absolute -right-20 top-10 h-64 w-64 rounded-full bg-brand/30 blur-3xl"></div>
        <div class="nf-float pointer-events-none absolute -left-16 bottom-24 h-56 w-56 rounded-full bg-white/10 blur-3xl" style="animation-delay: 1.5s"></div>

        <div class="relative z-10 nf-container flex min-h-[320px] flex-col items-center justify-center px-4 pb-24 pt-36 text-center sm:min-h-[380px] sm:pb-28 lg:pt-[11.5rem]">
            <span class="nf-in-up inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-white ring-1 ring-white/20">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                Get involved
            </span>

            <div class="nf-pop mt-6 rounded-2xl bg-white px-8 py-4 shadow-2xl shadow-navy-dark/40 sm:px-12 sm:py-5" style="animation-delay: .1s">
                <h1 class="text-3xl font-extrabold text-navy-dark sm:text-4xl lg:text-5xl">Become a <span class="text-brand">charity</span> volunteer</h1>
            </div>

            <p class="nf-in-up mt-6 max-w-xl text-base text-white/90 sm:text-lg" style="animation-delay: .25s">
                Register today and join us in our mission and vision to save and change lives for the better.
            </p>

            <nav class="nf-in-up mt-4 flex items-center justify-center gap-2 text-sm text-white/70" style="animation-delay: .35s">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                <span aria-hidden="true">&rsaquo;</span>
                <span class="text-white">Volunteer</span>
            </nav>
        </div>

        {{-- Animated layered waves --}}
        <div class="nf-waves" aria-hidden="true">
            <svg class="nf-waves__svg" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="nf-wave-vol" d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>
                <g class="nf-waves__parallax">
                    <use href="#nf-wave-vol" x="48" y="0" fill="rgba(255,255,255,0.7)" />
                    <use href="#nf-wave-vol" x="48" y="3" fill="rgba(255,255,255,0.5)" />
                    <use href="#nf-wave-vol" x="48" y="5" fill="rgba(255,255,255,0.3)" />
                    <use href="#nf-wave-vol" x="48" y="7" fill="#ffffff" />
                </g>
            </svg>
        </div>
    </section>

    {{-- ===================== FORM CARD ===================== --}}
    @php
        $skills = ['First aid', 'Teaching', 'Financial aid', 'Childcare', 'Human rights', 'Other'];
    @endphp

    <section id="form" class="scroll-mt-24 py-14 sm:py-16">
        <div class="nf-container">
            <div class="nf-reveal overflow-hidden rounded-3xl bg-cream shadow-xl ring-1 ring-black/5">
                <div class="grid lg:grid-cols-2">

                    {{-- Left: registration form --}}
                    <div class="p-7 sm:p-10">
                        <h2 class="nf-reveal text-2xl font-bold text-navy-dark sm:text-3xl">Register your interest</h2>
                        <p class="nf-reveal mt-3 text-sm leading-relaxed text-gray-600" data-reveal-delay="60">
                            Tell us a little about yourself and how you&rsquo;d like to help — our team will be in touch.
                        </p>

                        <form method="POST" action="{{ route('volunteer.store') }}" class="mt-6 space-y-5">
                            @csrf

                            @if ($errors->any())
                                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                                    <ul class="list-inside list-disc space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Contact details --}}
                            <div class="nf-reveal" data-reveal-delay="80">
                                <p class="mb-3 text-sm font-bold text-navy-dark">Contact details</p>
                                <div class="space-y-3">
                                    <input name="name" type="text" value="{{ old('name') }}" required placeholder="Your name *"
                                           class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                    <input name="phone" type="tel" value="{{ old('phone') }}" required placeholder="Phone number *"
                                           class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                    <input name="email" type="email" value="{{ old('email') }}" required placeholder="Email address *"
                                           class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                </div>
                            </div>

                            {{-- Skillsets --}}
                            <div class="nf-reveal" data-reveal-delay="160">
                                <p class="mb-3 text-sm font-bold text-navy-dark">Skillsets or area of interest</p>
                                <div class="grid grid-cols-2 gap-2.5">
                                    @foreach ($skills as $skill)
                                        <label class="nf-skill flex cursor-pointer items-center gap-2.5 rounded-lg border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-navy-dark transition-all duration-200 hover:border-brand">
                                            <input type="checkbox" name="skillsets[]" value="{{ $skill }}"
                                                   @checked(in_array($skill, old('skillsets', [])))
                                                   class="h-4 w-4 shrink-0 rounded border-gray-300 text-brand focus:ring-2 focus:ring-brand/30">
                                            <span>{{ $skill }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Additional info --}}
                            <div class="nf-reveal" data-reveal-delay="240">
                                <p class="mb-3 text-sm font-bold text-navy-dark">Additional information <span class="font-normal text-gray-400">(optional)</span></p>
                                <textarea name="additional_info" rows="4" placeholder="Anything else you would like us to know?"
                                          class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('additional_info') }}</textarea>
                            </div>

                            {{-- Privacy + submit --}}
                            <div class="nf-reveal" data-reveal-delay="320">
                                <label class="flex cursor-pointer items-start gap-3 text-sm text-gray-600">
                                    <input type="checkbox" name="agree" value="1" required @checked(old('agree'))
                                           class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-brand focus:ring-2 focus:ring-brand/30">
                                    <span>I understand and agree to the <a href="{{ route('privacy-policy') }}" class="font-semibold text-brand underline-offset-2 hover:underline">privacy policy</a>.</span>
                                </label>

                                <button type="submit" class="nf-cta-pulse btn-brand mt-5 w-full py-3">
                                    Submit request
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Right: image --}}
                    <div class="relative min-h-[320px] lg:min-h-full">
                        <img src="{{ asset('images/changinslives2.jpg') }}" alt="Children supported by Naeem Foundation"
                             class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-7 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wide text-white/80">Every hour counts</p>
                            <p class="mt-1 text-lg font-bold leading-tight">Give your time, change a life.</p>
                        </div>
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
                <a href="#form" class="btn-brand mt-7 px-7 py-3">
                    Get started now
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

@endsection
