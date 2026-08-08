@extends('layouts.app')

@section('title', 'Zakat — ' . config('app.name'))

@section('content')

    {{-- ===================== HERO + DONATE WIDGET ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-navy via-navy to-navy-dark">
        {{-- Decorative glows --}}
        <div class="pointer-events-none absolute -right-24 top-0 h-72 w-72 rounded-full bg-brand/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-24 -bottom-10 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>

        {{-- Wider image column + capped card keeps the widget compact. --}}
        <div class="relative grid items-stretch lg:grid-cols-[1.55fr_1fr]">
            {{-- Image + heading. Matches the shared donate-hero treatment: the title
                 is centred in the photo panel, with the padding-top clearing the
                 fixed header and items-center balancing what's left. --}}
            <div class="relative flex min-h-[420px] items-center pt-24 sm:min-h-[480px] lg:min-h-[560px] lg:pt-28">
                <img src="{{ asset('images/zakathero.png') }}" alt=""
                     class="absolute inset-0 h-full w-full object-cover">
                {{-- Blend image into the navy panel --}}
                <div class="absolute inset-0 bg-gradient-to-t from-navy/70 via-transparent to-transparent lg:bg-gradient-to-r lg:from-transparent lg:via-transparent lg:to-navy"></div>
                {{-- Darken the centre band so the heading holds up over the photo. --}}
                <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_bottom,rgba(18,45,60,0.22),rgba(18,45,60,0.6)_45%,rgba(18,45,60,0.28))]"></div>

                <div class="relative w-full px-6 py-10 sm:px-8 lg:px-10">
                    <div class="nf-reveal mx-auto max-w-4xl text-center text-white">
                        <h1 class="nf-hero-title text-4xl font-extrabold leading-[1.04] tracking-tight [text-wrap:balance] sm:text-5xl lg:text-6xl">
                            Purify Your Wealth, <span class="text-cream">Transform Lives</span>
                        </h1>
                        <span class="nf-hero-rule mx-auto mt-7 block h-1 rounded-full bg-brand" aria-hidden="true"></span>
                    </div>
                </div>
            </div>

            {{-- Donate widget (extra top padding on desktop so it clears the fixed header) --}}
            <div class="relative flex flex-col justify-center px-5 py-8 sm:px-10 lg:px-12 lg:pb-12 lg:pt-40" data-donate>
                <h2 class="text-2xl font-extrabold leading-tight text-white sm:text-3xl lg:text-4xl">Zakat Donations 2026</h2>
                <p class="mt-2 max-w-md text-sm leading-relaxed text-white/70">
                    Purify your wealth with 2.5% that brings food, water and hope to families in need.
                </p>

                <form method="POST" action="{{ route('donate.add') }}" data-donate-form
                      class="mt-5 w-full rounded-2xl bg-white p-5 shadow-2xl shadow-navy-dark/40 sm:p-6 lg:max-w-[28rem]">
                    @csrf
                    <input type="hidden" name="image" value="images/zakathero.png">

                    <p class="text-center text-sm font-bold uppercase tracking-wide text-brand">Choose an amount</p>

                    {{-- Cause dropdown — the donor can give to any cause from here. --}}
                    @include('partials.cause-select', ['selectedCause' => 'Zakat'])

                    {{-- Frequency --}}
                    <div class="mt-4 grid grid-cols-2 gap-3" data-choice-group>
                        <button type="button" data-choice data-value="one-off" class="nf-choice is-selected py-2.5">One-Off</button>
                        <button type="button" data-choice data-value="monthly" class="nf-choice py-2.5">Monthly</button>
                        <input type="hidden" name="frequency" data-choice-input value="one-off">
                    </div>

                    {{-- Amounts --}}
                    <div class="mt-3 grid grid-cols-4 gap-2" data-choice-group>
                        <button type="button" data-choice data-value="100" data-oneoff="100" data-monthly="25" class="nf-choice py-2">{{ region('symbol') }}100</button>
                        <button type="button" data-choice data-value="240" data-oneoff="240" data-monthly="50" class="nf-choice is-selected py-2">{{ region('symbol') }}240</button>
                        <button type="button" data-choice data-value="500" data-oneoff="500" data-monthly="100" class="nf-choice py-2">{{ region('symbol') }}500</button>
                        <button type="button" data-choice data-value="other" class="nf-choice py-2">{{ region('symbol') }}Other</button>
                        <input type="hidden" name="amount" data-choice-input data-amount-input value="240">
                    </div>

                    {{-- Custom amount (revealed when "Other" is chosen) --}}
                    <div data-custom-amount class="mt-3 hidden">
                        <label class="mb-1.5 block text-sm font-semibold text-navy-dark">Enter your amount</label>
                        <input type="number" min="1" step="1" inputmode="numeric" data-whole-number placeholder="e.g. 75" data-custom-amount-input
                               class="h-11 w-full rounded-md border border-gray-300 px-3 text-sm text-navy-dark focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
                    </div>

                    {{-- Donate --}}
                    <button type="submit" class="btn-navy mt-5 w-full py-3">
                        Donate Now
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    @include('partials.payment-icons')
                </form>
            </div>
        </div>
    </section>

    {{-- ===================== WHAT IS ZAKAT ===================== --}}
    <section class="pt-12 sm:pt-14">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: text --}}
            <div>
            <h2 class="mb-4 text-2xl font-bold text-brand">What is Zakat?</h2>
            <p class="text-sm leading-relaxed text-gray-700 sm:text-base">
                Zakat is more than just charity; it's an essential practice for all eligible Muslims. It's a duty to give
                2.5% of one's accumulated wealth annually to those in need. Unlike voluntary charity (Sadaqah), Zakat is
                mandatory for those who meet specific financial criteria. It helps the poor, the needy, and others in
                vulnerable situations. By giving Zakat, we cleanse our wealth, making it a blessing for ourselves and others.
            </p>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'zakat'])
            </div>
        </div>
    </section>

    {{-- Verse box --}}
    <section class="pt-8">
        <div class="nf-container">
            <div class="rounded-xl bg-navy px-6 py-8 text-white sm:px-10">
                <p class="text-center text-base font-medium sm:text-lg">
                    “Take from their wealth a charity by which you purify them and cause them to increase.”
                </p>
                <div class="mt-5 flex flex-col items-center gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <span class="order-2 text-sm text-white/80 sm:order-1">(Qur'an 9:103)</span>
                    <p class="order-1 text-xl leading-loose sm:order-2 sm:text-2xl" dir="rtl" lang="ar">
                        خُذْ مِنْ أَمْوَالِهِمْ صَدَقَةً تُطَهِّرُهُمْ وَتُزَكِّيهِمْ بِهَا وَصَلِّ عَلَيْهِمْ
                    </p>
                </div>
                <p class="mt-5 text-center text-xs leading-relaxed text-white/75 sm:text-sm">
                    This verse highlights how Zakat is not merely a financial transaction but a spiritual act that purifies
                    the giver's soul, fostering humility and gratitude. It aligns the giver's heart with the values of
                    compassion, detachment from material wealth, and a commitment to helping others, making Zakat a
                    transformative act both spiritually and socially.
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== CALCULATE YOUR ZAKAT (SEO + calculator link) ===================== --}}
    <section class="pt-12">
        <div class="nf-container">
            <div class="grid items-center gap-8 rounded-2xl bg-cream/60 p-6 ring-1 ring-navy/10 sm:p-8 lg:grid-cols-5 lg:gap-10 lg:p-10">
                <div class="lg:col-span-3">
                    <p class="mb-2 inline-block border-b-2 border-brand pb-1 text-xs font-bold uppercase tracking-wide text-brand">Calculate your Zakat</p>
                    <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Work out exactly how much Zakat you owe</h2>
                    <p class="mt-3 text-sm leading-relaxed text-gray-700 sm:text-base">
                        Zakat is one of the five pillars of Islam &mdash; an obligatory charity of <strong>2.5%</strong> on the
                        qualifying wealth every eligible Muslim has held for one lunar year above the <strong>nisab</strong>
                        threshold. Working it out by hand across cash, gold, silver, savings and investments can be confusing.
                    </p>
                    <p class="mt-3 text-sm leading-relaxed text-gray-700 sm:text-base">
                        Our free <strong>Zakat calculator</strong> makes it simple: enter your assets and liabilities and it
                        instantly shows your nisab status and the exact amount of Zakat due in your local currency &mdash; so
                        you can give with confidence and fulfil your obligation accurately, every year.
                    </p>
                </div>

                <div class="lg:col-span-2">
                    <div class="rounded-xl bg-white p-6 text-center shadow-lg shadow-navy/10">
                        <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-brand/10 text-brand">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h4" stroke-linecap="round"/></svg>
                        </span>
                        <p class="mt-4 text-lg font-bold text-navy-dark">Zakat Calculator</p>
                        <p class="mt-1 text-sm text-gray-500">Free, instant and accurate &mdash; takes under a minute.</p>
                        <a href="{{ route('zakat-calculator') }}" class="btn-brand mt-5 w-full justify-center py-3">
                            Calculate my Zakat
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== WHY GIVE ZAKAT ===================== --}}
    <section class="pt-12">
        <div class="nf-container space-y-4 text-sm leading-relaxed text-gray-700 sm:text-base">
            <h2 class="text-2xl font-bold text-brand">Why Give Zakat?</h2>
            <p>
                Giving Zakat brings immense rewards. It is an act that draws us closer to Allah (SWT), strengthens our
                faith, and protects us from harm. Through Zakat, we ensure our wealth benefits not only ourselves but also
                those around us, supporting the pillars of social justice, compassion, and empathy.
            </p>
            <div>
                <p>The Prophet (PBUH) said:</p>
                <p>"Charity does not decrease wealth."</p>
                <p class="text-base" dir="rtl" lang="ar">مَا نَقَصَتْ صَدَقَةٌ مِنْ مَالٍ</p>
                <p>(Sahih Muslim, Hadith 2588)</p>
            </div>
            <p>
                Zakat is a means to cultivate a peaceful and supportive community. Imagine how a single act of giving, just
                2.5% of your wealth, can bring change to lives and help build a society filled with compassion.
            </p>
        </div>
    </section>

    {{-- ===================== CHANGE LIVES BANNER ===================== --}}
    <section class="relative mt-12 overflow-hidden">
        <img src="{{ asset('images/zakatcenter.png') }}" alt="Your Zakat can change lives"
             class="h-72 w-full object-cover sm:h-80 lg:h-96">
        <div class="absolute inset-0 bg-navy-dark/65"></div>
        <div class="absolute inset-0 flex items-center">
            <div class="nf-container">
                <h2 class="text-3xl font-bold leading-tight text-white/90 sm:text-4xl lg:text-5xl">
                    YOUR ZAKAT CAN<br>CHANGE LIVES
                </h2>
                <p class="mt-3 text-base text-white/80 sm:text-lg">
                    Support struggling families<br>with your trusted donation.
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== HOW DOES ZAKAT BENEFIT US ===================== --}}
    <section class="pt-12 sm:pt-14">
        <div class="nf-container space-y-2 text-sm leading-relaxed text-gray-700 sm:text-base">
            <h2 class="mb-4 text-2xl font-bold text-brand">How Does Zakat Benefit Us?</h2>
            <p>Zakat brings blessings that reach beyond the material, transforming the lives of both the giver and the receiver:</p>
            <p><span class="font-semibold">Purifies Wealth:</span> Zakat purifies our income, ensuring that what we earn is clean and blessed.</p>
            <p><span class="font-semibold">Increases Blessings:</span> The act of giving multiplies our rewards, like planting a seed that grows into a tree with countless fruits.</p>
            <p><span class="font-semibold">Protects Against Hardships:</span> Just as Sadaqah shields us from calamities, Zakat offers protection and opens the door to Allah's mercy.</p>
            <p><span class="font-semibold">Promotes Equality:</span> By distributing wealth, Zakat promotes social balance and reduces economic inequalities.</p>
            <p class="pt-2">Allah (SWT) says in the Qur'an:</p>
            <p>"The example of those who spend their wealth in the way of Allah is like a seed [of grain] that sprouts seven ears; in every ear is a hundred grains."</p>
            <p class="text-base" dir="rtl" lang="ar">مَثَلُ الَّذِينَ يُنْفِقُونَ أَمْوَالَهُمْ فِي سَبِيلِ اللَّهِ كَمَثَلِ حَبَّةٍ أَنْبَتَتْ سَبْعَ سَنَابِلَ فِي كُلِّ سُنْبُلَةٍ مِائَةُ حَبَّةٍ</p>
            <p>(Qur'an 2:261)</p>
        </div>
    </section>

    {{-- ===================== WHO CAN RECEIVE ZAKAT ===================== --}}
    <section class="pt-12 sm:pt-14">
        <div class="nf-container space-y-2 text-sm leading-relaxed text-gray-700 sm:text-base">
            <h2 class="mb-4 text-2xl font-bold text-brand">Who Can Receive Zakat?</h2>
            <p>The Quran specifies eight categories of people who are eligible to receive Zakat, ensuring that this charity reaches those who truly need it:</p>
            <p><span class="font-semibold">The Poor:</span> Those with little or no income.</p>
            <p><span class="font-semibold">The Needy:</span> People who struggle to meet their basic needs.</p>
            <p><span class="font-semibold">Zakat Collectors:</span> Those who collect and manage Zakat funds.</p>
            <p><span class="font-semibold">New Muslims:</span> Individuals who are new to Islam and may need support.</p>
            <p><span class="font-semibold">Those in Debt:</span> Individuals burdened with debts they cannot repay.</p>
            <p><span class="font-semibold">In the Path of Allah:</span> Those who work for the betterment of society in line with Islamic values.</p>
            <p><span class="font-semibold">Stranded Travelers:</span> Those who are far from home and need assistance.</p>
            <p class="pt-2">"Zakat expenditures are only for the poor and for the needy and for those employed to collect it…"</p>
            <p class="text-base" dir="rtl" lang="ar">إِنَّمَا الصَّدَقَاتُ لِلْفُقَرَاءِ وَالْمَسَاكِينِ وَالْعَامِلِينَ عَلَيْهَا</p>
            <p>(Qur'an 9:60)</p>
        </div>
    </section>

    {{-- ===================== CLOSING CTA ===================== --}}
    <section class="py-12 sm:py-14">
        <div class="nf-container space-y-3 text-sm leading-relaxed text-gray-700 sm:text-base">
            <h2 class="mb-4 text-2xl font-bold text-brand">Who Can Receive Zakat?</h2>
            <p>
                Imagine the impact we can make together. Let us fulfill this pillar of Islam with dedication, knowing that
                our Zakat is bringing blessings to others and ourselves. Whether large or small, every contribution helps.
                Give Zakat, and experience the profound joy and blessings that come with being part of something greater.
            </p>
            <p>Join us today in making a difference. Give Zakat, and be part of a legacy of compassion, unity, and hope.</p>
        </div>
    </section>

@endsection
