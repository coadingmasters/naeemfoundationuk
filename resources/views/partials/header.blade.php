@php
    $navLinks = [
        ['label' => 'About', 'url' => route('about')],
        ['label' => 'Ramadan 2026', 'url' => '#'],
        ['label' => 'Projects', 'url' => '#'],
        ['label' => 'Appeals', 'url' => '#'],
        ['label' => 'Community Center', 'url' => '#'],
        ['label' => 'Hajj 2026', 'url' => '#'],
    ];
@endphp

<header class="sticky top-0 z-50 bg-white">
    <div class="nf-container pt-4">
        {{-- Floating rounded header card (white nav + maroon verse joined) --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            {{-- Top white bar --}}
            <div class="flex h-20 items-center justify-between gap-4 px-5 sm:px-7">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto">
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden items-center gap-6 xl:gap-8 lg:flex">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['url'] }}"
                           class="text-sm font-bold text-navy transition-colors hover:text-brand">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-3">
                    <a href="#" class="btn-brand">
                        Donate
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    {{-- Mobile menu toggle --}}
                    <button type="button" data-menu-toggle
                            class="grid h-10 w-10 place-items-center rounded-md text-navy lg:hidden"
                            aria-label="Toggle menu" aria-expanded="false">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile nav panel --}}
            <nav data-menu-panel class="hidden border-t border-gray-100 bg-white lg:hidden">
                <div class="flex flex-col px-5 py-2 sm:px-7">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['url'] }}"
                           class="border-b border-gray-100 py-3 text-sm font-semibold text-navy last:border-0 hover:text-brand">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </nav>

            {{-- Arabic verse bar --}}
            <div class="bg-brand text-white">
                <div class="px-5 py-2 text-center sm:px-7">
                    <p class="text-sm leading-relaxed" dir="rtl" lang="ar">
                        قُلْ مَنْ يُنَجِّيكُمْ مِنْ ظُلُمَاتِ الْبَرِّ وَالْبَحْرِ تَدْعُونَهُ تَضَرُّعًا وَخُفْيَةً لَئِنْ أَنْجَانَا مِنْ هَٰذِهِ لَنَكُونَنَّ مِنَ الشَّاكِرِينَ ٦٣
                    </p>
                </div>
            </div>
        </div>
    </div>
</header>
