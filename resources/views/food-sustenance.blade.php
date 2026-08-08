@extends('layouts.app')

@section('title', 'Food & Sustenance — ' . config('app.name'))

@php
    // "Our Projects" cards — managed in the admin dashboard, with a resilient fallback.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => 'Empowering tomorrow’s leaders today at Naeem Foundation.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // Focus areas
    $focus = [
        ['title' => 'Empowering through Nutrition', 'text' => 'Providing essential sustenance to fuel lives and aspirations.'],
        ['title' => 'Bridging the Hunger Gap', 'text' => 'Ensuring access to nutritious meals for vulnerable communities.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'A Family <span class="text-cream">Fed</span> is a Family Empowered.',
        'heroSubtitle' => 'In a world where access to food is a basic human right, we at Naeem Foundation are committed to ensuring that no one goes hungry — providing direct and dignified support to those facing food insecurity.',
        'widgetCauses' => ['Food & Sustenance', 'Ration Pack', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: text --}}
            <div>
                <p class="text-lg font-bold italic text-brand sm:text-xl">“A meal today brings hope for tomorrow.”</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Hunger is not just the absence of food — it’s the absence of hope. For families struggling to put
                    meals on the table, the future can feel uncertain and overwhelming. At Naeem Foundation, we believe
                    that no one should have to go hungry. Every meal we provide brings warmth, dignity, and a renewed
                    sense of hope to those in need. By coming together, we can turn hunger into hope and help families
                    not just survive, but thrive.
                </p>

                <h2 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Our focus</h2>
                <ul class="mt-4 space-y-4">
                    @foreach ($focus as $item)
                        <li class="flex gap-3">
                            <span class="mt-1 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <p class="text-sm leading-relaxed text-gray-600 sm:text-base">
                                <span class="font-bold text-navy-dark">{{ $item['title'] }}:</span> {{ $item['text'] }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'food-sustenance'])
            </div>
        </div>
    </section>

    {{-- ===================== FEED A FAMILY CARD ===================== --}}
    @php
        // Preset amounts. The full month's ration is the default selection.
        $familyCost = 150;
        $familyAmounts = [30, 50, $familyCost];

        // Currencies come from the region config so this list can never claim
        // something the region switcher and PayPal don't actually support.
        $currencies = collect(config('countries.list', []))->pluck('currency')->implode(', ');
    @endphp
    <section class="pb-14 sm:pb-16">
        <div class="nf-container">
            <div class="nf-reveal overflow-hidden rounded-3xl bg-navy shadow-2xl shadow-navy/25">
                <div class="grid lg:grid-cols-[1.15fr_1fr]">

                    {{-- Left: the details + the single amount --}}
                    <div class="p-7 text-white sm:p-10 lg:p-12">
                        <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Join Us in Nourishing Lives</p>
                        <h2 class="mt-2 text-2xl font-bold leading-snug sm:text-3xl">Feed a Family for a Month</h2>
                        <p class="mt-4 text-sm leading-relaxed text-white/80 sm:text-base">
                            Your generous donation can make a tangible difference in the lives of those struggling to put
                            food on the table. By supporting our food assistance programs, you are helping to alleviate
                            hunger, promote health, and empower individuals to build a better future.
                        </p>

                        <form method="POST" action="{{ route('donate.add') }}" data-cart-skip data-family-form class="mt-7">
                            @csrf
                            <input type="hidden" name="cause" value="Food & Sustenance">
                            <input type="hidden" name="image" value="images/changinslives2.jpg">
                            <input type="hidden" name="amount" value="{{ $familyCost }}" data-family-amount-input>
                            <input type="hidden" name="frequency" value="one-off" data-family-freq>

                            {{-- Amount --}}
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70">Choose an amount</p>
                            <div class="mt-3 grid grid-cols-3 gap-2 sm:max-w-md">
                                @foreach ($familyAmounts as $a)
                                    <button type="button" data-family-amount="{{ $a }}"
                                            class="nf-limb-choice {{ $a === $familyCost ? 'is-selected' : '' }}">{{ money($a, 0) }}</button>
                                @endforeach
                            </div>

                            {{-- The month-long claim belongs to the top amount only, so
                                 it stays tied to that figure rather than the selection. --}}
                            <p class="mt-3 flex items-start gap-2 text-xs leading-relaxed text-white/65 sm:text-sm">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#e9b9c6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01" stroke-linecap="round"/></svg>
                                <span>{{ money($familyCost, 0) }} provides a full ration pack &mdash; flour, rice, pulses, oil and essentials &mdash; feeding one family for a month.</span>
                            </p>

                            {{-- Give once, or every month (the recurring option below). --}}
                            <p class="mt-6 text-xs font-semibold uppercase tracking-wider text-white/70">How often?</p>
                            <div class="mt-3 grid grid-cols-2 gap-2 sm:max-w-xs">
                                <button type="button" data-family-choice="one-off" class="nf-limb-choice is-selected">One-Off</button>
                                <button type="button" data-family-choice="monthly" class="nf-limb-choice">Monthly</button>
                            </div>

                            <button type="submit" class="btn-brand group mt-6 w-full justify-center py-3.5 text-base sm:w-auto sm:px-9">
                                Donate <span data-family-total>{{ money($familyCost, 0) }}</span>
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>

                        {{-- Donation options --}}
                        <div class="mt-8 border-t border-white/15 pt-6">
                            <h3 class="text-base font-bold text-white">Donation Options</h3>
                            <ul class="mt-3 space-y-2.5 text-sm text-white/75">
                                @foreach ([
                                    ['Single Donations:', 'Suggested amounts '.implode(', ', array_map(fn ($a) => money($a, 0), $familyAmounts)).'.'],
                                    ['Recurring Donations:', 'Set up a monthly contribution to feed a family every month.'],
                                    ['Currency Options:', $currencies.' — set by your region at the top of the page.'],
                                ] as $j => $opt)
                                    <li class="nf-reveal flex gap-2" data-reveal-delay="{{ 140 + $j * 90 }}">
                                        <span class="font-semibold text-[#e9b9c6]">&rsaquo;</span>
                                        <span><span class="font-semibold text-white">{{ $opt[0] }}</span> {{ $opt[1] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Right: image --}}
                    <div class="relative min-h-[280px] overflow-hidden lg:min-h-full">
                        <img src="{{ asset('images/changinslives2.jpg') }}" alt="A family receiving a food ration pack"
                             class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1.4s] ease-out hover:scale-105">
                        {{-- Blends the photo into the navy card on the left edge. --}}
                        <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-navy/60 to-transparent lg:bg-gradient-to-r lg:from-navy lg:via-navy/25 lg:to-transparent" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== BE THE CHANGE (CTA) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Give your support</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Be the Change They Need</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                No child should sleep hungry. Your donation today puts food on a family’s table and restores their
                dignity. Together, we can turn hunger into hope.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Support the Cause
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS (dynamic carousel) ===================== --}}
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

@push('scripts')
<script>
    // Feed a Family card — amount presets plus the one-off / monthly switch.
    // The submitted value always comes from the hidden input, never a label.
    (function () {
        const form = document.querySelector('[data-family-form]');
        if (!form) return;

        const freq = form.querySelector('[data-family-freq]');
        const amountInput = form.querySelector('[data-family-amount-input]');
        const total = form.querySelector('[data-family-total]');
        const amounts = [...form.querySelectorAll('[data-family-amount]')];
        const freqBtns = [...form.querySelectorAll('[data-family-choice]')];

        amounts.forEach((btn) => btn.addEventListener('click', () => {
            amounts.forEach((b) => b.classList.toggle('is-selected', b === btn));
            amountInput.value = btn.dataset.familyAmount;
            // Mirror the chosen amount on the button so it always agrees.
            if (total) total.textContent = btn.textContent.trim();
        }));

        freqBtns.forEach((btn) => btn.addEventListener('click', () => {
            freqBtns.forEach((b) => b.classList.toggle('is-selected', b === btn));
            freq.value = btn.dataset.familyChoice;
        }));
    })();
</script>
@endpush

@endsection
