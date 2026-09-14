@extends('layouts.app')

@section('title', 'Ramadan Timetable ' . config('ramadan-timetable.year') . ' — ' . config('app.name'))

@php
    $year = config('ramadan-timetable.year');
    $location = config('ramadan-timetable.location');

    // Offer the PDF when it has been uploaded; otherwise let the donor print.
    $pdf = config('ramadan-timetable.pdf');
    $hasPdf = $pdf && file_exists(public_path($pdf));

    $columns = ['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib/Iftar', 'Isha'];

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
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="nf-noprint relative overflow-hidden">
        <img src="{{ asset('images/changinslives2.jpg') }}" alt="Iftar table"
             class="h-72 w-full object-cover sm:h-96 lg:h-[420px]">
        <div class="absolute inset-0 bg-navy-dark/55"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center">
            <h1 class="rounded-lg bg-white px-6 py-3 text-2xl font-extrabold text-navy-dark shadow-xl sm:px-10 sm:py-4 sm:text-3xl lg:text-4xl">
                Ramadan Timetable <span class="text-brand">{{ $year }}</span>
            </h1>

            <nav aria-label="Breadcrumb" class="mt-4 text-sm text-white/85">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <span class="font-semibold text-white">Ramadan Timetable</span>
            </nav>
        </div>
    </section>

    {{-- ===================== TIMETABLE ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container">
            <h2 class="text-center text-2xl font-bold text-brand sm:text-3xl">Seher &amp; Iftar Schedule {{ $year }}</h2>
            <p class="mt-2 text-center text-sm text-gray-500">{{ $location }}</p>

            @unless ($hasTimes)
                <p class="mx-auto mt-5 max-w-2xl rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-center text-sm text-amber-800">
                    Prayer times for {{ $year }} are being finalised and will be published here shortly.
                    Please confirm timings with your local mosque.
                </p>
            @endunless

            {{-- Table --}}
            <div class="mx-auto mt-8 max-w-3xl overflow-hidden rounded-2xl bg-brand p-4 shadow-xl sm:p-6">
                <div class="text-center text-white">
                    <p class="text-lg font-extrabold tracking-wide sm:text-2xl">RAMADAN KAREEM</p>
                </div>

                <div class="mt-4 overflow-x-auto rounded-lg">
                    <table class="w-full min-w-[640px] border-collapse bg-white text-left text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-cream text-navy-dark">
                                <th scope="col" class="px-3 py-2.5 font-bold">Date</th>
                                <th scope="col" class="px-3 py-2.5 font-bold">Ramadan</th>
                                @foreach ($columns as $column)
                                    <th scope="col" class="px-3 py-2.5 font-bold">{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $i => $row)
                                <tr class="{{ $i % 2 ? 'bg-gray-50' : 'bg-white' }} border-t border-gray-100">
                                    <td class="whitespace-nowrap px-3 py-2 text-gray-700">{{ $row['date']->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-2 font-semibold text-navy-dark">{{ $row['label'] }}</td>

                                    @for ($c = 0; $c < 6; $c++)
                                        <td class="whitespace-nowrap px-3 py-2 text-gray-700">
                                            {{ $row['times'][$c] ?? '—' }}
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex flex-wrap items-center justify-center gap-x-5 gap-y-1 text-xs text-white/85">
                    <span>{{ region('phone') }}</span>
                    <span>naeemfoundation.co.uk</span>
                </div>
            </div>

            {{-- Download / print --}}
            <div class="nf-noprint mt-6 text-center">
                @if ($hasPdf)
                    <a href="{{ asset($pdf) }}" download class="btn-brand px-6 py-2.5">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Download Ramadan timetable {{ $year }}
                    </a>
                @else
                    <button type="button" data-print-page class="btn-brand px-6 py-2.5">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2M6 14h12v7H6z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Print / save timetable {{ $year }}
                    </button>
                @endif
            </div>
        </div>
    </section>

    {{-- ===================== ARTICLE ===================== --}}
    <section class="nf-noprint pb-14">
        <div class="nf-container">
            <article class="mx-auto max-w-3xl space-y-8 text-sm leading-relaxed text-gray-600 sm:text-base">

                <div>
                    <h2 class="text-xl font-bold text-navy-dark sm:text-2xl">
                        Fasting in Ramadan {{ $year }}: The Pillar of Patience and Reflection in Islam
                    </h2>
                    <p class="mt-3">
                        Ramadan is the most sacred and anticipated month of the Islamic calendar — a time for deep
                        spiritual reflection, purification and abundant mercy. Fasting during Ramadan is one of the Five
                        Pillars of Islam, making it a cornerstone of every Muslim’s faith and practice. This month-long
                        period of fasting goes beyond abstaining from food and drink; it is a time to cleanse the soul,
                        strengthen self-discipline, and seek the mercy and forgiveness of Allah (SWT).
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-navy-dark sm:text-2xl">The Purpose of Fasting in Ramadan</h2>
                    <p class="mt-3">
                        Fasting during Ramadan is a powerful way for Muslims to draw closer to Allah. It provides an
                        opportunity to develop taqwa (God-consciousness), practise patience, and express gratitude for
                        blessings. Allah (SWT) says in the Qur’an:
                    </p>
                    <blockquote class="mt-4 border-l-4 border-brand pl-4 text-sm font-bold italic text-brand sm:text-base">
                        “O you who have believed, decreed upon you is fasting as it was decreed upon those before you
                        that you may become righteous.” (Qur’an 2:183)
                    </blockquote>
                    <p class="mt-4">
                        This sacred practice encourages believers to empathise with the less fortunate and focus on
                        spiritual growth, kindness and self-discipline throughout the blessed month.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-navy-dark sm:text-2xl">Rewards and Blessings of Fasting</h2>
                    <p class="mt-3">
                        Fasting in Ramadan brings immense reward. As mentioned in authentic Hadith, fasting is a unique
                        act of worship that Allah Himself promises to reward generously:
                    </p>

                    <p class="mt-4"><span class="font-bold text-navy-dark">Forgiveness of Sins:</span></p>
                    <p>The Prophet Muhammad (PBUH) said:</p>
                    <p class="italic">
                        “Whoever fasts during Ramadan with faith and seeking reward from Allah will have his past sins
                        forgiven.” (Bukhari, Muslim)
                    </p>

                    <p class="mt-4"><span class="font-bold text-navy-dark">Exclusive Reward of Fasting:</span></p>
                    <p>The Prophet (PBUH) also said:</p>
                    <p class="italic">
                        “Every deed of the son of Adam is for him except fasting, for it is for Me, and I shall reward it
                        (as I like).” (Sahih Muslim)
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-navy-dark sm:text-2xl">When Does Ramadan {{ $year }} Begin?</h2>
                    <p class="mt-3">
                        Ramadan in {{ $year }} is expected to begin on the evening of Tuesday, 17th February {{ $year }},
                        with the first fast on Wednesday, 18th February {{ $year }}. The exact start depends on the
                        sighting of the new moon and may vary by region.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-navy-dark sm:text-2xl">When Does Ramadan {{ $year }} End?</h2>
                    <p class="mt-3">
                        Ramadan {{ $year }} is expected to conclude on Thursday, 19th March {{ $year }}, with Eid al-Fitr
                        celebrations beginning thereafter. The final date is confirmed upon moon sighting in each
                        locality.
                    </p>
                </div>
            </article>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS ===================== --}}
    <section class="nf-noprint pb-16 sm:pb-20">
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
