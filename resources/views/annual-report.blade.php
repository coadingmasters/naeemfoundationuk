@extends('layouts.app')

@section('title', 'Annual Report — ' . config('app.name'))

{{-- Light hero → keep the header solid. --}}
@section('header-solid', 'yes')

@section('content')

    {{-- ===================== TITLE BAND ===================== --}}
    <section class="bg-cream">
        <div class="nf-container py-12 text-center sm:py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand">Who We Are</p>
            <h1 class="mt-2 text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl">Annual Reports</h1>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 sm:text-base">
                Full transparency on how your donations are used. Read our audited accounts, impact figures and
                programme highlights — published every year.
            </p>
        </div>
    </section>

    {{-- ===================== REPORTS ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container">

            @if ($reports->isEmpty())
                <div class="mx-auto max-w-lg rounded-2xl border border-dashed border-brand/30 bg-cream/50 p-10 text-center">
                    <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-brand/10 text-brand">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" stroke-linejoin="round"/><path d="M14 3v5h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <h2 class="mt-4 text-lg font-bold text-navy-dark">Reports coming soon</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Our latest annual reports will be published here shortly.
                    </p>
                </div>
            @else
                <div class="space-y-12">
                    @foreach ($reports as $i => $report)
                        {{-- Left: report card · Right: the details as prose --}}
                        <article class="nf-reveal grid items-center gap-8 lg:grid-cols-5 lg:gap-12"
                                 style="transition-delay: {{ $i * 90 }}ms">

                            {{-- Left: the card --}}
                            <div class="lg:col-span-2">
                                <a href="{{ $report->file_url }}" target="_blank" rel="noopener"
                                   class="group block overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                                    <div class="relative h-56 overflow-hidden bg-navy sm:h-64">
                                        @if ($report->cover_url)
                                            <img src="{{ $report->cover_url }}" alt="{{ $report->title }}"
                                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        @else
                                            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-navy to-navy-dark text-white">
                                                <svg class="h-16 w-16 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" stroke-linejoin="round"/><path d="M14 3v5h5M9 13h6M9 17h4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </div>
                                        @endif
                                        <span class="absolute left-3 top-3 rounded-full bg-brand px-3 py-1 text-xs font-bold text-white shadow">
                                            {{ $report->year }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 border-t border-gray-100 px-5 py-3.5">
                                        <span class="text-xs font-semibold text-navy-dark">{{ basename($report->file_path) }}</span>
                                        <span class="text-xs font-bold uppercase tracking-wide text-brand">PDF</span>
                                    </div>
                                </a>
                            </div>

                            {{-- Right: details in paragraph form --}}
                            <div class="lg:col-span-3">
                                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Financial year {{ $report->year }}</p>
                                <h2 class="mt-2 text-2xl font-bold leading-snug text-navy-dark sm:text-3xl">{{ $report->title }}</h2>

                                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                                    {{ $report->summary ?: 'This report sets out how your donations were received and spent over the year — our programmes, the people reached, and the audited financial statements behind them.' }}
                                </p>

                                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                                    Inside you'll find our chair's foreword, impact figures across food, water, healthcare
                                    and education, a breakdown of income and expenditure, and our governance and trustee
                                    details. We publish these openly so you can see exactly where your generosity goes.
                                </p>

                                <div class="mt-6 flex flex-wrap gap-3">
                                    <a href="{{ $report->file_url }}" target="_blank" rel="noopener" class="btn-brand px-6 py-2.5">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Download PDF
                                    </a>
                                    <a href="{{ $report->file_url }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-2 rounded-md border border-navy/20 px-6 py-2.5 text-sm font-semibold text-navy transition-colors hover:bg-navy hover:text-white">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" stroke-linejoin="round"/><circle cx="12" cy="12" r="3"/></svg>
                                        View online
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Every penny counts</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Your Trust, Our Accountability</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                We publish our accounts openly because your generosity deserves nothing less. Questions about how we
                spend? Get in touch any time.
            </p>
            <a href="{{ route('donate.checkout') }}" class="btn-brand mt-7 px-7 py-3">
                Donate Now
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

@endsection
