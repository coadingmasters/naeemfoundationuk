@extends('layouts.app')

@section('title', 'Eid Gifts for Children — ' . config('app.name'))

@php
    // "Our Projects" cards — managed in the admin dashboard.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => 'Empowering tomorrow’s leaders today at Naeem Foundation.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // What your gift provides — icon = single-path SVG.
    $gifts = [
        ['title' => 'New Eid Clothes', 'text' => 'A brand-new outfit so every child can dress with pride on the morning of Eid.', 'icon' => '<path d="M16 3l5 3-2.5 4L16 9v11H8V9l-2.5 1L3 6l5-3 4 2 4-2z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Toys & Games', 'text' => 'A gift to unwrap and a reason to smile — the simple magic of childhood joy.', 'icon' => '<path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Books & School Supplies', 'text' => 'Notebooks, pens and storybooks that keep a child’s love of learning alive.', 'icon' => '<path d="M12 6C10 4.5 7 4.5 5 6v12c2-1.5 5-1.5 7 0 2-1.5 5-1.5 7 0V6c-2-1.5-5-1.5-7 0z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'A Festive Meal', 'text' => 'Sweet treats and a warm Eid meal shared with family, just like every child deserves.', 'icon' => '<path d="M4 11h16a8 8 0 0 1-8 8 8 8 0 0 1-8-8zM12 3v4" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/supporton.png',
        'heroEyebrow' => 'Eid Gifts for Children',
        'heroTitle' => 'Turn a Hard Ramadan Into a <span class="text-cream">Joyful</span> Eid',
        'heroSubtitle' => 'For a child who has fasted through hardship, one small gift can turn Eid from an ordinary day into a memory they treasure forever. Give the gift of a smile this Eid.',
        'widgetCauses' => ['Eid Gifts', 'Orphan Support', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid gap-10 lg:grid-cols-2 lg:items-center lg:gap-14">
            <div>
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">The joy every child deserves</p>
                <h2 class="mt-4 text-2xl font-bold text-navy-dark sm:text-3xl">Every Child Deserves to Feel the Magic of Eid</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Eid is a time of celebration, togetherness and gratitude. Yet for families facing poverty, the joy of
                    Eid is often out of reach — no new clothes, no gifts, and no festive meal to mark the end of a month
                    of devotion. For the children who fasted with patience and prayer, this can be heartbreaking.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your generosity changes that. An Eid gift is more than a present — it is a message to a child that
                    they are seen, valued and not forgotten.
                </p>
            </div>

            <div class="space-y-4">
                <figure class="border-l-4 border-brand bg-cream/60 p-5">
                    <blockquote class="text-sm font-semibold italic text-navy-dark sm:text-base">“He is not a believer whose stomach is filled while the neighbour to his side goes hungry.”</blockquote>
                    <figcaption class="mt-2 text-xs text-gray-500">— Al-Adab Al-Mufrad 112</figcaption>
                </figure>
                <figure class="border-l-4 border-brand bg-cream/60 p-5">
                    <blockquote class="text-sm font-semibold italic text-navy-dark sm:text-base">“The best of you are those who are best to their families.”</blockquote>
                    <figcaption class="mt-2 text-xs text-gray-500">— Jami' at-Tirmidhi 3895</figcaption>
                </figure>
            </div>
        </div>
    </section>

    {{-- ===================== WHAT YOUR GIFT PROVIDES ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">The gift of a smile</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">What Your Gift Provides</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-500 sm:text-base">
                    From toys and clothes to books and a warm meal, every donation helps a deserving child celebrate Eid
                    with dignity and delight.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($gifts as $gift)
                    <div class="nf-reveal flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-cream text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">{!! $gift['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 font-bold text-navy-dark">{{ $gift['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $gift['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== HOW IT WORKS (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="nf-reveal">
                        <span class="text-3xl font-extrabold text-brand/25">01</span>
                        <h3 class="mt-1 text-lg font-bold text-navy-dark">You Give</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Choose an amount that sponsors an Eid gift for one child — or several. 100% of your donation
                            reaches those in need.
                        </p>
                    </div>
                    <div class="nf-reveal">
                        <span class="text-3xl font-extrabold text-brand/25">02</span>
                        <h3 class="mt-1 text-lg font-bold text-navy-dark">We Deliver</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Our teams hand-deliver gifts to orphans, widows’ children and families in extreme poverty —
                            before the morning of Eid.
                        </p>
                    </div>
                    <div class="nf-reveal">
                        <span class="text-3xl font-extrabold text-brand/25">03</span>
                        <h3 class="mt-1 text-lg font-bold text-navy-dark">They Smile</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            A child unwraps a gift, wears new clothes and feels the joy of Eid — knowing they are loved
                            and remembered.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== STORY ===================== --}}
    <section class="pb-16">
        <div class="nf-container">
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="grid lg:grid-cols-2">
                    {{-- Image --}}
                    <div class="relative min-h-[280px] lg:min-h-full">
                        <img src="{{ asset('images/changinslives1.jpg') }}" alt="A child’s Eid smile"
                             class="absolute inset-0 h-full w-full object-cover">
                        <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-brand px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-white shadow-lg">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            A true story
                        </span>
                    </div>

                    {{-- Text --}}
                    <div class="p-6 sm:p-10">
                        <h2 class="text-2xl font-bold text-brand">A Heartfelt Thank You from Fatima</h2>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                            Fatima is a mother of three, including her son Ayaan who studies at the local Maktab. Living in
                            a small rented house, her family struggles to make ends meet — and Eid gifts were always
                            beyond reach.
                        </p>

                        <figure class="mt-6 rounded-xl border-l-4 border-brand bg-cream/60 p-5">
                            <blockquote class="text-sm leading-relaxed italic text-navy-dark sm:text-base">
                                “Alhamdulillah, I am so thankful to Naeem Foundation for the beautiful Eid gifts they
                                distributed. This allowed my children to experience the joy of Eid and celebrate just like
                                other children. I pray for blessings upon those who made this possible. May Allah reward
                                them abundantly.”
                            </blockquote>
                            <figcaption class="mt-3 text-xs font-semibold text-gray-500">— Fatima, mother of Ayaan</figcaption>
                        </figure>

                        <a href="#donate" class="btn-navy mt-6 px-7 py-2.5">
                            Sponsor a Child’s Eid
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Be the change they need</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Give a Child an Eid to Remember</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                No child should feel forgotten on the happiest day of the year. Your gift — large or small — turns a
                difficult Ramadan into a joyful Eid, and earns you the everlasting reward of bringing happiness to a
                child’s heart.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Gift an Eid Smile
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS (dynamic) ===================== --}}
    <section class="py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Livelihood programs</p>
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
