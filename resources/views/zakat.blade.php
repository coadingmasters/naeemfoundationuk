@extends('layouts.app')

@section('title', 'Zakat — ' . config('app.name'))

@section('content')

    {{-- ===================== HERO + DONATE WIDGET ===================== --}}
    <section class="bg-navy">
        <div class="grid items-stretch lg:grid-cols-2">
            {{-- Image --}}
            <div class="relative min-h-[260px] sm:min-h-[340px]">
                <img src="{{ asset('images/zakathero.png') }}" alt="Zakat donations"
                     class="absolute inset-0 h-full w-full object-cover">
            </div>

            {{-- Donate widget --}}
            <div class="px-5 py-8 sm:px-10 lg:px-12 lg:py-10" data-donate>
                <h2 class="text-xl font-bold text-white sm:text-2xl">Zakat Donations 2026</h2>

                <div class="mt-4 rounded-xl bg-gray-100 p-5 shadow-sm sm:p-6">
                    <p class="text-center text-sm font-bold uppercase tracking-wide text-brand">Choose an amount</p>

                    {{-- Frequency --}}
                    <div class="mt-4 grid grid-cols-2 gap-3" data-choice-group>
                        <button type="button" data-choice class="nf-choice is-selected py-2.5">One-Off</button>
                        <button type="button" data-choice class="nf-choice py-2.5">Monthly</button>
                    </div>

                    {{-- Amounts --}}
                    <div class="mt-3 grid grid-cols-4 gap-2" data-choice-group>
                        <button type="button" data-choice class="nf-choice py-2">£100</button>
                        <button type="button" data-choice class="nf-choice is-selected py-2">£240</button>
                        <button type="button" data-choice class="nf-choice py-2">£240</button>
                        <button type="button" data-choice class="nf-choice py-2">£Other</button>
                    </div>

                    {{-- Donate --}}
                    <button type="button" class="btn-navy mt-5 w-full py-3">
                        Donate Now
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    {{-- Payment methods --}}
                    <div class="mt-5 flex flex-wrap items-center justify-center gap-x-4 gap-y-2">
                        <img src="{{ asset('images/firstpaymenticon.png') }}" alt="Registered with Fundraising Regulator" class="h-7 w-auto">
                        <img src="{{ asset('images/otheralinoneicons.png') }}" alt="Visa, Mastercard, PayPal" class="h-6 w-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== WHAT IS ZAKAT ===================== --}}
    <section class="pt-12 sm:pt-14">
        <div class="nf-container">
            <h2 class="mb-4 text-2xl font-bold text-brand">What is Zakat?</h2>
            <p class="text-sm leading-relaxed text-gray-700 sm:text-base">
                Zakat is more than just charity; it's an essential practice for all eligible Muslims. It's a duty to give
                2.5% of one's accumulated wealth annually to those in need. Unlike voluntary charity (Sadaqah), Zakat is
                mandatory for those who meet specific financial criteria. It helps the poor, the needy, and others in
                vulnerable situations. By giving Zakat, we cleanse our wealth, making it a blessing for ourselves and others.
            </p>
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
