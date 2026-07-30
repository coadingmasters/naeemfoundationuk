@extends('layouts.app')

@section('title', 'Widows Support — ' . config('app.name'))

@php
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
        ]);
    }

    // How the appeal supports widows.
    $support = [
        ['title' => 'Monthly Food Support', 'text' => 'A regular supply of essential groceries so no family goes hungry.', 'icon' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Skills & Livelihoods', 'text' => 'Training and small grants that help widows earn a dignified, independent income.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Children’s Education', 'text' => 'School fees, uniforms and books so her children can keep learning.', 'icon' => '<path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11M9 8h4M9 12h6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Healthcare & Dignity', 'text' => 'Access to medicine, care and a compassionate hand in times of need.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];

    $levels = [
        ['amount' => 30, 'label' => 'Monthly Groceries', 'note' => 'Essential food to keep a widow and her children fed each month.'],
        ['amount' => 60, 'label' => 'Family Support', 'note' => 'Food, basic bills and children’s school essentials.'],
        ['amount' => 100, 'label' => 'A Path to Independence', 'note' => 'Full support plus skills training towards a sustainable income.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/supporton.png',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'Stand With a <span class="text-cream">Widow</span> in Need',
        'heroSubtitle' => 'When a woman loses her husband, she often loses her only support. Your gift restores her dignity, feeds her children and helps her stand on her own feet.',
        'widgetCauses' => ['Widows Support', 'Orphan Care', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO + VIDEO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: text --}}
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;The one who cares for a widow is like a warrior in the path of Allah.&rdquo;</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Behind every widow is a family that suddenly has no one to provide for them. Many are left to raise
                    children alone, with no income, no safety net and little hope &mdash; facing hunger, debt and the
                    heartbreak of watching their children go without.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    At Naeem Foundation, our Widows Appeal walks beside these families &mdash; providing food, healthcare
                    and the skills a mother needs to rebuild her life with dignity and strength.
                </p>
            </div>
            {{-- Right: animated video --}}
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'widows'])
            </div>
        </div>
    </section>

    {{-- ===================== HOW WE HELP ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">More than a meal</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">How Your Support Helps</h2>
            </div>

            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($support as $i => $p)
                    <div class="nf-reveal flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy/10"
                         data-reveal-delay="{{ $i * 60 }}">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $p['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 text-base font-bold text-navy-dark">{{ $p['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $p['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== SUPPORT LEVELS ===================== --}}
    <section class="pb-14">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: levels --}}
            <div class="nf-reveal">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Sponsor a Widow</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    A small monthly gift can change everything for a widow and her children. Choose a level that&rsquo;s
                    right for you:
                </p>

                <div class="mt-5 space-y-3">
                    @foreach ($levels as $l)
                        <div class="flex items-center gap-4 rounded-xl border border-brand/15 bg-cream/50 p-4 transition-colors hover:border-brand/40">
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-brand text-sm font-extrabold text-white">{{ money($l['amount'], 0) }}</span>
                            <div>
                                <p class="font-bold text-navy-dark">{{ $l['label'] }} <span class="text-xs font-medium text-gray-400">/ month</span></p>
                                <p class="text-xs leading-relaxed text-gray-500">{{ $l['note'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-5 text-xs leading-relaxed text-gray-400">
                    Prefer a one-off gift? Any amount brings immediate relief to a family in crisis &mdash; give whatever
                    you can using the form above.
                </p>
            </div>

            {{-- Right: image + "join us" card --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="overflow-hidden rounded-2xl bg-cream shadow-sm ring-1 ring-navy/10">
                    <div class="relative h-64 overflow-hidden sm:h-80">
                        <img src="{{ asset('images/changinslives1.jpg') }}" alt="A widow and her children" class="h-full w-full object-cover">
                        <span class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 to-transparent"></span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-navy-dark">Join Us in Making a Difference</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Your kindness tells a struggling mother she is not forgotten. Together, we can turn her
                            hardship into hope &mdash; and help her build a future for her family.
                        </p>
                        <a href="#donate" class="btn-brand mt-5 px-6 py-2.5">
                            Support a Widow
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA (navy) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Be her support</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Restore Her Strength & Dignity</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                No mother should have to choose between feeding her children and keeping a roof over their heads. Your
                generosity today gives a widow the support she needs to stand tall and provide for her family once more.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Support a Widow
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS ===================== --}}
    <section class="py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Our programmes</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Our Projects</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    Naeem Foundation is a vibrant and compassionate NGO dedicated to improving the lives of individuals
                    and communities in need, with a solid spotlight on compassion.
                </p>
            </div>

            <div class="mt-10">
                @include('partials.projects-carousel')
            </div>
        </div>
    </section>

@endsection
