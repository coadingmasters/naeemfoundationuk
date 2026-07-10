@extends('layouts.app')

@section('title', 'Thank You — ' . config('app.name'))

@section('content')

    <section class="nf-thankyou-bg relative overflow-hidden py-12 sm:py-16 lg:py-20">
        <div class="nf-container">

            {{-- ===== Card ===== --}}
            <div class="nf-reveal relative mx-auto max-w-3xl overflow-hidden rounded-3xl bg-[#fdfaf3] px-6 py-12 text-center shadow-2xl sm:px-10 sm:py-16">

                {{-- Decorative blobs --}}
                <span class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-[#e6e9f5]" aria-hidden="true"></span>
                <span class="pointer-events-none absolute left-24 top-24 h-8 w-8 rounded-full bg-[#dfe4f2]" aria-hidden="true"></span>
                <span class="pointer-events-none absolute -right-20 -top-10 h-64 w-64 rounded-full bg-[#fdf1d8]" aria-hidden="true"></span>
                <span class="pointer-events-none absolute -bottom-20 -left-10 h-60 w-60 rounded-full bg-[#fbe9c6]" aria-hidden="true"></span>
                <span class="pointer-events-none absolute -bottom-16 -right-16 h-56 w-56 rounded-full bg-[#e6e9f5]" aria-hidden="true"></span>

                {{-- Content sits above the blobs --}}
                <div class="relative">

                    {{-- Illustration --}}
                    <div class="relative mx-auto h-36 w-40">
                        <svg class="nf-heart absolute left-1/2 top-0 h-9 w-9" viewBox="0 0 24 24" fill="#e9a380" aria-hidden="true">
                            <path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z"/>
                        </svg>

                        <div class="absolute bottom-0 left-1/2 grid h-28 w-36 -translate-x-1/2 place-items-center rounded-full bg-[#fbe3cf]">
                            <span class="text-5xl leading-none" role="img" aria-label="Helping hands">🤝</span>
                        </div>
                    </div>

                    {{-- Headline --}}
                    <h1 class="mt-7 text-3xl font-extrabold leading-tight text-navy-dark sm:text-4xl lg:text-5xl">
                        Thank You for Your Support <span role="img" aria-label="yellow heart">💛</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-600 sm:text-lg">
                        Your contribution is making a real difference.
                    </p>

                    {{-- Status pill --}}
                    <div class="mt-8 inline-flex flex-wrap items-center justify-center gap-x-4 gap-y-2 rounded-2xl bg-white px-6 py-4 shadow-lg shadow-black/5">
                        <span class="text-sm text-gray-600">
                            Donation: <span class="font-bold text-navy-dark">£{{ number_format($total, 2) }}</span>
                        </span>

                        <span class="hidden h-5 w-px bg-gray-200 sm:block" aria-hidden="true"></span>

                        <span class="text-sm text-gray-600">
                            Status:
                            <span class="ml-1 inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-0.5 font-semibold text-green-700">
                                Successful
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </span>
                    </div>

                    <p class="mt-3 text-xs text-gray-500">
                        Reference: <span class="font-semibold text-navy-dark">{{ $reference }}</span>
                    </p>

                    {{-- CTA --}}
                    <div class="mt-8">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-full bg-navy-dark px-8 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-navy hover:shadow-xl">
                            See Impact
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
