@php
    // Icons for the "Who We Are" dropdown (single-path SVGs, brand-coloured).
    $navGroups = [
        [
            'label' => 'Who We Are',
            'active' => request()->routeIs('about', 'history', 'annual-report', 'careers', 'news'),
            'children' => [
                ['label' => 'About Us', 'desc' => 'Our story & mission', 'url' => route('about'),
                 'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['label' => 'History', 'desc' => 'Two decades of impact', 'url' => route('history'),
                 'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['label' => 'Annual Report', 'desc' => 'Transparency & accounts', 'url' => route('annual-report'),
                 'icon' => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" stroke-linejoin="round"/><path d="M14 3v5h5M9 13l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['label' => 'Career', 'desc' => 'Join our team', 'url' => route('careers'),
                 'icon' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18" stroke-linecap="round" stroke-linejoin="round"/>'],
                ['label' => 'News & Press', 'desc' => 'Latest updates', 'url' => route('news'),
                 'icon' => '<path d="M4 5h13v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" stroke-linejoin="round"/><path d="M17 9h2a1 1 0 0 1 1 1v8a2 2 0 0 1-2 2M8 9h6M8 13h6M8 17h3" stroke-linecap="round" stroke-linejoin="round"/>'],
            ],
        ],
        ['label' => 'Giving', 'mega' => true, 'active' => request()->routeIs('give.*', 'zakat', 'zakat-ul-fitr', 'eid-gifts', 'ramadan-food-packs', 'fidya', 'sadaqah', 'sehri-iftar', 'water-well')],
        ['label' => 'Projects', 'url' => '#'],
        ['label' => 'Appeals', 'url' => '#'],
        ['label' => 'Community Center', 'url' => '#'],
        ['label' => 'Hajj 2027', 'url' => route('hajj'), 'active' => request()->routeIs('hajj')],
    ];

    // Resolve a giving menu item to its URL (dedicated route or auto placeholder).
    $givingUrl = fn ($item) => ! empty($item['route']) ? route($item['route']) : route('give.'.$item['slug']);

    $arrowSvg = '<svg class="nf-mega__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    $verse = 'قُلْ مَنْ يُنَجِّيكُمْ مِنْ ظُلُمَاتِ الْبَرِّ وَالْبَحْرِ تَدْعُونَهُ تَضَرُّعًا وَخُفْيَةً لَئِنْ أَنْجَانَا مِنْ هَٰذِهِ لَنَكُونَنَّ مِنَ الشَّاكِرِينَ ٦٣';
@endphp

<header class="sticky top-0 z-50 bg-white shadow-md">
    {{-- ===== Main bar (full width) ===== --}}
    <div class="nf-container">
        <div class="flex h-20 items-center justify-between gap-4">
            {{-- Logo + name --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto sm:h-14">
                <!-- <span class="hidden leading-tight sm:block">
                    <span class="block text-base font-extrabold text-navy sm:text-lg">{{ config('app.name') }}</span>
                    <span class="block text-[11px] font-semibold uppercase tracking-wider text-brand">Changing Lives</span>
                </span> -->
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden items-center gap-6 xl:gap-8 lg:flex">
                @foreach ($navGroups as $item)
                    @if (!empty($item['mega']))
                        {{-- ===== Giving mega-dropdown ===== --}}
                        <div class="nf-dd nf-dd--mega">
                            <button type="button"
                                    class="flex items-center gap-1.5 text-sm font-bold transition-colors hover:text-brand {{ ($item['active'] ?? false) ? 'text-brand' : 'text-navy' }}"
                                    aria-haspopup="true">
                                {{ $item['label'] }}
                                <svg class="nf-dd__chev h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            <div class="nf-dd__menu nf-dd__menu--mega" role="menu">
                                <div class="nf-mega__cols">
                                    @foreach (['appeals', 'islamic'] as $key)
                                        <div class="nf-mega__col">
                                            <span class="nf-mega__head">{{ config('giving.'.$key.'.heading') }}</span>
                                            <div class="nf-mega__list">
                                                @foreach (array_merge(config('giving.'.$key.'.items'), config('giving.'.$key.'.featured', [])) as $g)
                                                    <a href="{{ $givingUrl($g) }}" class="nf-mega__item">
                                                        <span class="nf-mega__dot"></span>
                                                        <span class="flex-1 leading-tight">{{ $g['title'] }}</span>
                                                        {!! $arrowSvg !!}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif (!empty($item['children']))
                        {{-- ===== Who We Are dropdown ===== --}}
                        <div class="nf-dd">
                            <button type="button"
                                    class="flex items-center gap-1.5 text-sm font-bold transition-colors hover:text-brand {{ ($item['active'] ?? false) ? 'text-brand' : 'text-navy' }}"
                                    aria-haspopup="true">
                                {{ $item['label'] }}
                                <svg class="nf-dd__chev h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            <div class="nf-dd__menu" role="menu">
                                <p class="px-3 pb-2 pt-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">{{ $item['label'] }}</p>
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['url'] }}" class="nf-dd__item" role="menuitem">
                                        <span class="nf-dd__ico">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">{!! $child['icon'] !!}</svg>
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block leading-tight">{{ $child['label'] }}</span>
                                            <span class="block text-xs font-normal leading-tight text-gray-400">{{ $child['desc'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}" class="text-sm font-bold transition-colors hover:text-brand {{ ($item['active'] ?? false) ? 'text-brand' : 'text-navy' }}">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Right --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('ask-mufti') }}"
                   class="hidden items-center gap-2 rounded-md border px-5 py-2.5 text-sm font-semibold transition-colors md:inline-flex {{ request()->routeIs('ask-mufti') ? 'border-brand bg-brand text-white' : 'border-brand/20 bg-cream text-brand hover:border-brand hover:bg-brand hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Ask a Mufti
                </a>
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
    </div>

    {{-- ===== Mobile nav panel ===== --}}
    <nav data-menu-panel class="hidden border-t border-gray-100 bg-white lg:hidden">
        <div class="nf-container flex flex-col py-2">
            @foreach ($navGroups as $item)
                @if (!empty($item['mega']))
                    <div class="border-b border-gray-100">
                        <button type="button" data-subnav-toggle aria-expanded="false"
                                class="flex w-full items-center justify-between py-3 text-sm font-semibold text-navy">
                            {{ $item['label'] }}
                            <svg data-subnav-chev class="h-4 w-4 text-brand transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div data-subnav class="hidden flex-col pb-2">
                            @foreach (['appeals', 'islamic'] as $key)
                                <p class="px-1 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wider text-brand">{{ config('giving.'.$key.'.heading') }}</p>
                                @foreach (array_merge(config('giving.'.$key.'.items'), config('giving.'.$key.'.featured', [])) as $g)
                                    <a href="{{ $givingUrl($g) }}"
                                       class="ml-2 border-l-2 border-cream py-2 pl-4 text-sm font-medium text-navy transition-colors hover:border-brand hover:text-brand">
                                        {{ $g['title'] }}
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @elseif (!empty($item['children']))
                    <div class="border-b border-gray-100">
                        <button type="button" data-subnav-toggle aria-expanded="false"
                                class="flex w-full items-center justify-between py-3 text-sm font-semibold text-navy">
                            {{ $item['label'] }}
                            <svg data-subnav-chev class="h-4 w-4 text-brand transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div data-subnav class="hidden flex-col pb-2">
                            @foreach ($item['children'] as $child)
                                <a href="{{ $child['url'] }}"
                                   class="ml-2 border-l-2 border-cream py-2.5 pl-4 text-sm font-medium text-navy transition-colors hover:border-brand hover:text-brand">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['url'] }}"
                       class="border-b border-gray-100 py-3 text-sm font-semibold text-navy last:border-0 hover:text-brand">
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach

            <a href="{{ route('ask-mufti') }}"
               class="mt-3 inline-flex items-center justify-center gap-2 rounded-md bg-cream px-4 py-2.5 text-sm font-semibold text-brand transition-colors hover:bg-brand hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Ask a Mufti
            </a>
        </div>
    </nav>

    {{-- ===== Arabic verse marquee — full width, scrolls left → right ===== --}}
    <div class="nf-marquee nf-marquee--ltr overflow-hidden bg-brand py-2 text-white">
        <div class="nf-marquee__track" dir="ltr">
            @for ($i = 0; $i < 2; $i++)
                <div class="nf-marquee__group" @if ($i === 1) aria-hidden="true" @endif>
                    @for ($j = 0; $j < 3; $j++)
                        <span class="nf-marquee__item text-sm leading-relaxed" dir="rtl" lang="ar">{{ $verse }}</span>
                        <span class="nf-marquee__sep" aria-hidden="true">۞</span>
                    @endfor
                </div>
            @endfor
        </div>
    </div>
</header>
