@extends('layouts.app')

@section('title', 'Prosthetic Limb — ' . config('app.name'))

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

    // Donation Details card — one source for the target figures, so the copy,
    // the stat tiles and the preset amounts can never drift apart.
    $limbCost = 1200;   // one complete limb, including fitting + rehabilitation
    $limbCount = 10;    // limbs the appeal aims to fund
    $limbGoal = $limbCost * $limbCount;

    $limbStats = [
        ['value' => money($limbCost, 0), 'label' => 'One complete limb'],
        ['value' => money($limbGoal, 0), 'label' => 'Fundraising goal'],
        ['value' => $limbCount, 'label' => 'Lives changed'],
    ];

    // Preset amounts. The full limb cost is pre-selected.
    $limbAmounts = [100, 300, 600, $limbCost];

    // Two-up galleries
    $introGallery = [
        ['image' => 'images/supporton.png', 'alt' => 'Fitting a prosthetic limb'],
        ['image' => 'images/changinslives4.jpg', 'alt' => 'A child raising their hand after treatment'],
    ];

    $creamGallery = [
        ['image' => 'images/changinslives1.jpg', 'alt' => 'A child standing with a new prosthetic limb'],
        ['image' => 'images/changinslives4.jpg', 'alt' => 'Rehabilitation support session'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives4.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'Give the Gift of Mobility <span class="text-cream">Restore Lives</span>',
        'heroSubtitle' => 'A prosthetic limb restores far more than movement — it restores independence, confidence and the ability to earn a livelihood.',
        'widgetCauses' => ['Prosthetic Limb', 'Medical Care', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: copy --}}
            <div>
                <h2 class="text-xl font-bold leading-snug text-navy-dark sm:text-2xl">
                    Every Step Counts, Help Someone Walk Again
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    For many people, losing a limb means losing far more than physical movement. It affects independence,
                    confidence, and the ability to earn a livelihood. Everyday tasks we take for granted — walking,
                    standing, and working — become difficult challenges. With your support, we can restore mobility and
                    give them the freedom to live life fully.
                </p>

                <h2 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Restoring Hope and Dignity</h2>
                <p class="mt-2 text-sm font-bold italic text-brand">“A Limb Today, A Life Transformed Forever”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    A prosthetic limb does more than help someone walk. It restores dignity, confidence, and hope. With
                    proper support, a person can return to work, provide for their family, and rebuild their future. Your
                    donation directly transforms a life by replacing hardship with strength and opportunity.
                </p>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'prosthetic-limb'])
            </div>
        </div>
    </section>

    {{-- ===================== DONATION DETAILS CARD ===================== --}}
    {{-- Promoted out of the cream panel below so the target and the amount picker
         sit together, straight after the video. --}}
    <section class="pb-14 sm:pb-16">
        <div class="nf-container">
            <div class="nf-reveal overflow-hidden rounded-3xl bg-navy shadow-2xl shadow-navy/25">
                <div class="grid lg:grid-cols-[1.15fr_1fr]">

                    {{-- Left: the details + amount picker --}}
                    <div class="p-7 text-white sm:p-10 lg:p-12">
                        <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Donation Details</p>
                        <h2 class="mt-2 text-2xl font-bold leading-snug sm:text-3xl">
                            &ldquo;Minimum Target: One Limb, Maximum Impact&rdquo;
                        </h2>
                        <p class="mt-4 text-sm leading-relaxed text-white/80 sm:text-base">
                            The cost of <span class="font-bold text-white">one complete prosthetic limb is {{ money($limbCost, 0) }}</span>.
                            Our goal is to raise <span class="font-bold text-white">{{ money($limbGoal, 0) }}</span>, which will provide
                            <span class="font-bold text-white">{{ $limbCount }} life-changing prosthetic limbs</span>. By contributing,
                            you make a direct and tangible difference in the lives of those in need.
                        </p>

                        {{-- The three numbers at a glance --}}
                        <div class="mt-7 grid grid-cols-3 gap-3">
                            @foreach ($limbStats as $i => $stat)
                                <div class="nf-reveal rounded-xl bg-white/10 p-3 text-center ring-1 ring-white/15 transition-colors duration-300 hover:bg-white/15 sm:p-4"
                                     data-reveal-delay="{{ 120 + $i * 90 }}">
                                    <p class="text-lg font-extrabold leading-none text-white sm:text-2xl">{{ $stat['value'] }}</p>
                                    <p class="mt-1.5 text-[11px] leading-tight text-white/65 sm:text-xs">{{ $stat['label'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Amount picker → straight into the donation basket. --}}
                        <form method="POST" action="{{ route('donate.add') }}" data-cart-skip data-limb-form class="mt-7">
                            @csrf
                            <input type="hidden" name="cause" value="Prosthetic Limb">
                            <input type="hidden" name="frequency" value="one-off">
                            <input type="hidden" name="image" value="images/changinslives4.jpg">
                            <input type="hidden" name="amount" value="{{ $limbCost }}" data-limb-amount>

                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70">Choose an amount</p>
                            <div class="mt-3 grid grid-cols-3 gap-2 sm:grid-cols-5">
                                @foreach ($limbAmounts as $amount)
                                    <button type="button" data-limb-choice="{{ $amount }}"
                                            class="nf-limb-choice {{ $amount === $limbCost ? 'is-selected' : '' }}">{{ money($amount, 0) }}</button>
                                @endforeach
                                <button type="button" data-limb-choice="other" class="nf-limb-choice">Other</button>
                            </div>

                            {{-- Revealed by "Other" --}}
                            <div class="mt-3 hidden" data-limb-custom>
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-white/70">{{ region('symbol') }}</span>
                                    <input type="number" min="1" step="1" inputmode="numeric" data-limb-custom-input
                                           placeholder="Enter your amount"
                                           class="h-12 w-full rounded-lg border border-white/25 bg-white/10 pl-9 pr-3 text-sm font-semibold text-white placeholder-white/45 outline-none transition focus:border-white/60 focus:ring-2 focus:ring-white/20">
                                </div>
                            </div>

                            <button type="submit" class="btn-brand group mt-5 w-full justify-center py-3.5 text-base sm:w-auto sm:px-9">
                                Donate Now
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <p class="mt-3 text-xs text-white/55">{{ money($limbCost, 0) }} funds one complete limb, including fitting and rehabilitation.</p>
                        </form>
                    </div>

                    {{-- Right: image --}}
                    <div class="relative min-h-[280px] overflow-hidden lg:min-h-full">
                        <img src="{{ asset('images/supporton.png') }}" alt="A prosthetic limb being fitted"
                             class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1.4s] ease-out hover:scale-105">
                        {{-- Blends the photo into the navy card on the left edge. --}}
                        <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-navy/60 to-transparent lg:bg-gradient-to-r lg:from-navy lg:via-navy/25 lg:to-transparent" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== DETAIL PANEL (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">

                {{-- Why your support matters --}}
                <h3 class="text-xl font-bold text-navy-dark sm:text-2xl">Why Your Support Matters</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“Change a Life with Every Step”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Providing a prosthetic limb allows someone to regain independence and actively participate in society.
                    Children can play and attend school, while adults can work and contribute to their families and
                    communities. Each limb is a step toward a brighter, more hopeful future.
                </p>

                {{-- Our focus --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Our Focus</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“Ensuring Every Limb Comes with Care”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    This appeal provides complete prosthetic limbs, including medical fitting, adjustment, and
                    rehabilitation support. Every donation ensures recipients not only receive a limb, but also the
                    guidance needed to use it effectively and safely.
                </p>

                {{-- Donation Details now lives in its own card above, next to the
                     amount picker — deliberately not repeated here. --}}

                {{-- Gallery --}}
                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    @foreach ($creamGallery as $i => $shot)
                        <div class="nf-reveal group overflow-hidden rounded-xl" data-reveal-delay="{{ $i * 120 }}">
                            <img src="{{ asset($shot['image']) }}" alt="{{ $shot['alt'] }}"
                                 class="h-64 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 sm:h-72 lg:h-80">
                        </div>
                    @endforeach
                </div>

                {{-- Your impact --}}
                <h3 class="mt-9 text-xl font-bold text-navy-dark sm:text-2xl">Your Impact</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“From Hardship to Hope”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your support restores independence and dignity. With every prosthetic limb provided, a person can walk
                    again, return to work, and actively engage with their family and community. Your contribution
                    transforms lives and creates lasting change.
                </p>

                {{-- Make a difference --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Your Donation Can Make a Difference</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“Step Forward, Be the Reason Someone Walks Again”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Every contribution brings us closer to providing a complete prosthetic limb to someone in need. Your
                    generosity can restore mobility, rebuild confidence, and give a person the chance to live life
                    independently and with dignity.
                </p>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <a href="#donate" class="btn-brand px-7 py-3">
                        Donate a Limb
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS (dynamic carousel) ===================== --}}
    <section class="pb-16 sm:pb-20">
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
    // Donation Details card — amount picker. The submitted value always comes
    // from the hidden input, so the server never trusts a button label.
    (function () {
        const form = document.querySelector('[data-limb-form]');
        if (!form) return;

        const amount = form.querySelector('[data-limb-amount]');
        const customWrap = form.querySelector('[data-limb-custom]');
        const customInput = form.querySelector('[data-limb-custom-input]');
        const choices = [...form.querySelectorAll('[data-limb-choice]')];

        choices.forEach((btn) => btn.addEventListener('click', () => {
            choices.forEach((b) => b.classList.toggle('is-selected', b === btn));

            if (btn.dataset.limbChoice === 'other') {
                customWrap.classList.remove('hidden');
                customInput.focus();
                amount.value = customInput.value || '';
            } else {
                customWrap.classList.add('hidden');
                amount.value = btn.dataset.limbChoice;
            }
        }));

        customInput?.addEventListener('input', () => {
            // Whole pounds only, matching the rest of the donation forms.
            customInput.value = customInput.value.replace(/\D/g, '');
            amount.value = customInput.value;
        });

        // Block a submit with no amount rather than bouncing off validation.
        form.addEventListener('submit', (e) => {
            if (!amount.value || Number(amount.value) < 1) {
                e.preventDefault();
                customWrap.classList.remove('hidden');
                customInput.focus();
            }
        });
    })();
</script>
@endpush

@endsection
