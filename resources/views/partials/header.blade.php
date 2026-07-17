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
        ['label' => 'Community Centre', 'url' => route('community-centre'), 'active' => request()->routeIs('community-centre')],
        ['label' => 'Hajj 2027', 'url' => route('hajj'), 'active' => request()->routeIs('hajj')],
    ];

    // Top-level icons, used by the mobile drawer.
    $navIcons = [
        'Who We Are' => '<circle cx="12" cy="8" r="3.2"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0" stroke-linecap="round"/>',
        'Giving' => '<path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z" stroke-linejoin="round"/>',
        'Community Centre' => '<path d="M3 10.5 12 4l9 6.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 10v10h14V10" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 20v-5h4v5" stroke-linecap="round" stroke-linejoin="round"/>',
        'Hajj 2027' => '<path d="M4 20h16" stroke-linecap="round"/><path d="M6 20V9l6-4 6 4v11" stroke-linejoin="round"/><path d="M10 20v-4a2 2 0 0 1 4 0v4" stroke-linecap="round"/>',
    ];

    // Resolve a giving menu item to its URL (dedicated route or auto placeholder).
    $givingUrl = fn ($item) => ! empty($item['route']) ? route($item['route']) : route('give.'.$item['slug']);

    // Giving mega-menu: three link columns + a promo image card.
    $megaColumns = [
        ['heading' => config('giving.appeals.heading'), 'items' => config('giving.appeals.items')],
        ['heading' => config('giving.islamic.heading'), 'items' => config('giving.islamic.items')],
        ['heading' => 'This Ramadan', 'items' => config('giving.islamic.featured', [])],
    ];
    $megaPromo = [
        'image' => 'images/zakatcenter.png',
        'eyebrow' => 'Not sure where to give?',
        'title' => 'Where Most Needed',
        'url' => route('donate.checkout'),
    ];

    $arrowSvg = '<svg class="nf-mega__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    // Transparent overlay by default; light-hero pages request a solid header
    // via @section('header-solid', 'yes').
    $overlay = ! ($solid ?? false);
@endphp

<header data-header class="nf-header {{ $overlay ? 'nf-header--overlay' : '' }}">

    {{-- ===== Top utility bar ===== --}}
    <div class="nf-topbar" data-topbar>
        <div class="nf-container flex h-9 items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                <a href="tel:+442070788118" class="nf-topbar__link shrink-0">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24 11.36 11.36 0 0 0 3.57.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.36 11.36 0 0 0 .57 3.57 1 1 0 0 1-.24 1.02l-2.21 2.2Z"/></svg>
                    <span class="hidden sm:inline">Donation Line:</span> +44 20 7078 8118
                </a>
                <span class="nf-topbar__sep hidden md:inline-block"></span>
                <span class="hidden truncate md:inline">Registered Charity No. <strong class="font-semibold text-white">1199466</strong></span>
            </div>

            <div class="flex shrink-0 items-center gap-3">
                <a href="mailto:Contact@naeemfoundation.co.uk" class="nf-topbar__link hidden lg:inline-flex">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5-8-5V6l8 5 8-5v2Z"/></svg>
                    Contact@naeemfoundation.co.uk
                </a>
                <span class="nf-topbar__sep hidden lg:inline-block"></span>
                {{-- Socials are the first thing to go when width is tight. --}}
                <div class="hidden items-center gap-1.5 sm:flex">
                    <a href="#" aria-label="Facebook" class="nf-topbar__social"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M13 22v-8h2.6l.4-3H13V9c0-.9.3-1.5 1.6-1.5H16V5c-.3 0-1.3-.1-2.3-.1-2.3 0-3.7 1.3-3.7 3.8V11H8v3h2v8h3Z"/></svg></a>
                    <a href="#" aria-label="Instagram" class="nf-topbar__social"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
                    <a href="#" aria-label="TikTok" class="nf-topbar__social"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M16 3a5 5 0 0 0 5 5v3a8 8 0 0 1-5-1.8V15a6 6 0 1 1-6-6c.3 0 .7 0 1 .1v3.2A2.8 2.8 0 1 0 13 15V3h3Z"/></svg></a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Main bar (full width) ===== --}}
    <div class="nf-container relative z-10">
        <div class="flex h-20 items-center justify-between gap-4 lg:h-24">
            {{-- Left: logo + nav --}}
            <div class="flex items-center gap-6 xl:gap-10">
                {{-- Brand lockup: emblem in a white badge + wordmark (the emblem's own
                     lettering is unreadable at header size, so the wordmark carries the name). --}}
                <a href="{{ route('home') }}" class="nf-brand shrink-0" aria-label="{{ config('app.name') }} — home">
                    <span class="nf-brand__badge">
                        <img src="{{ asset('images/logo.png') }}" alt=""
                             class="h-12 w-12 sm:h-[52px] sm:w-[52px] lg:h-[58px] lg:w-[58px]">
                    </span>
                    <span class="nf-brand__text">
                        <span class="nf-brand__name">Naeem Foundation</span>
                        <span class="nf-brand__tag">Building Hopes &amp; Futures</span>
                    </span>
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden items-center gap-6 xl:gap-8 lg:flex">
                @foreach ($navGroups as $item)
                    @if (!empty($item['mega']))
                        {{-- ===== Giving mega-dropdown ===== --}}
                        <div class="nf-dd nf-dd--mega">
                            <button type="button"
                                    class="nf-nav-item flex items-center gap-1.5 text-sm font-bold {{ ($item['active'] ?? false) ? 'is-active' : '' }}"
                                    aria-haspopup="true">
                                {{ $item['label'] }}
                                <svg class="nf-dd__chev h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            <div class="nf-dd__menu nf-dd__menu--mega" role="menu">
                                <div class="nf-mega__grid">
                                    @foreach ($megaColumns as $col)
                                        <div class="nf-mega__col">
                                            <span class="nf-mega__head">{{ $col['heading'] }}</span>
                                            <div class="nf-mega__list">
                                                @foreach ($col['items'] as $g)
                                                    <a href="{{ $givingUrl($g) }}" class="nf-mega__item">
                                                        <span class="flex-1 leading-tight">{{ $g['title'] }}</span>
                                                        {!! $arrowSvg !!}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Promo image card --}}
                                    <a href="{{ $megaPromo['url'] }}" class="nf-mega__promo group">
                                        <span class="nf-mega__promo-media">
                                            <img src="{{ asset($megaPromo['image']) }}" alt="">
                                        </span>
                                        <span class="nf-mega__promo-band">
                                            <span class="nf-mega__promo-eyebrow">{{ $megaPromo['eyebrow'] }}</span>
                                            <span class="nf-mega__promo-title">
                                                {{ $megaPromo['title'] }}
                                                <svg class="nf-mega__promo-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @elseif (!empty($item['children']))
                        {{-- ===== Who We Are dropdown ===== --}}
                        <div class="nf-dd">
                            <button type="button"
                                    class="nf-nav-item flex items-center gap-1.5 text-sm font-bold {{ ($item['active'] ?? false) ? 'is-active' : '' }}"
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
                        <a href="{{ $item['url'] }}" class="nf-nav-item text-sm font-bold {{ ($item['active'] ?? false) ? 'is-active' : '' }}">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
                </nav>
            </div>

            {{-- Right: basket + actions --}}
            <div class="flex items-center gap-3">
                {{-- Basket --}}
                @include('partials.cart')

                <a href="{{ route('ask-mufti') }}"
                   class="hidden items-center gap-2 rounded-full border px-5 py-2.5 text-sm font-semibold transition-colors lg:inline-flex {{ request()->routeIs('ask-mufti') ? 'border-brand bg-brand text-white' : 'border-brand/25 bg-cream text-brand hover:border-brand hover:bg-brand hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Ask a Mufti
                </a>
                {{-- Desktop only — on mobile the Donate CTA lives in the drawer,
                     which keeps the small header down to logo, basket and menu. --}}
                <a href="{{ route('donate.checkout') }}" class="nf-donate nf-donate--desktop">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z"/>
                    </svg>
                    Donate
                </a>

                {{-- Mobile menu toggle (morphs into a cross while the drawer is out) --}}
                <button type="button" data-menu-toggle
                        class="nf-header__toggle nf-burger"
                        aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-drawer">
                    <span class="nf-burger__box" aria-hidden="true">
                        <span class="nf-burger__line"></span>
                        <span class="nf-burger__line"></span>
                        <span class="nf-burger__line"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== Mobile drawer (slides in from the left) ===== --}}
    <div class="nf-drawer__backdrop lg:hidden" data-menu-backdrop aria-hidden="true"></div>

    <nav id="mobile-drawer" data-menu-panel class="nf-drawer lg:hidden" aria-label="Main menu">
        <div class="flex flex-col px-5 py-2">
            @foreach ($navGroups as $item)
                @php $ico = $navIcons[$item['label']] ?? ''; @endphp

                @if (!empty($item['mega']) || !empty($item['children']))
                    <div class="nf-drawer__row border-b border-gray-100">
                        <button type="button" data-subnav-toggle aria-expanded="false"
                                class="nf-drawer__link {{ ($item['active'] ?? false) ? 'is-active' : '' }}">
                            <span class="nf-drawer__ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $ico !!}</svg>
                            </span>
                            <span class="flex-1 text-left">{{ $item['label'] }}</span>
                            <svg data-subnav-chev class="nf-drawer__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div data-subnav class="hidden flex-col pb-2">
                            @if (!empty($item['mega']))
                                @foreach ($megaColumns as $col)
                                    <p class="nf-drawer__group">{{ $col['heading'] }}</p>
                                    @foreach ($col['items'] as $g)
                                        <a href="{{ $givingUrl($g) }}" class="nf-drawer__sub">{{ $g['title'] }}</a>
                                    @endforeach
                                @endforeach
                            @else
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['url'] }}" class="nf-drawer__sub">{{ $child['label'] }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @else
                    <a href="{{ $item['url'] }}"
                       class="nf-drawer__row nf-drawer__link border-b border-gray-100 {{ ($item['active'] ?? false) ? 'is-active' : '' }}">
                        <span class="nf-drawer__ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $ico !!}</svg>
                        </span>
                        <span class="flex-1 text-left">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach

            {{-- Primary actions live here rather than in the compact mobile bar. --}}
            <div class="nf-drawer__row nf-drawer__cta">
                <a href="{{ route('donate.checkout') }}" class="nf-donate w-full justify-center">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z"/>
                    </svg>
                    Donate
                </a>

                <a href="{{ route('ask-mufti') }}"
                   class="mt-2.5 inline-flex w-full items-center justify-center gap-2 rounded-full border border-brand/25 bg-cream px-4 py-2.5 text-sm font-semibold text-brand transition-colors hover:bg-brand hover:text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Ask a Mufti
                </a>
            </div>

            {{-- Contact details, since the top bar sheds them on small screens. --}}
            <div class="nf-drawer__row mt-5 border-t border-gray-100 pt-4 text-xs text-gray-500">
                <a href="tel:+442070788118" class="nf-topbar__link font-semibold text-navy hover:text-brand">
                    <svg class="h-3.5 w-3.5 shrink-0 text-brand" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24 11.36 11.36 0 0 0 3.57.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.36 11.36 0 0 0 .57 3.57 1 1 0 0 1-.24 1.02l-2.21 2.2Z"/></svg>
                    +44 20 7078 8118
                </a>
                <a href="mailto:Contact@naeemfoundation.co.uk" class="mt-2 block break-all hover:text-brand">Contact@naeemfoundation.co.uk</a>
                <p class="mt-2">Registered Charity No. <strong class="text-navy">1199466</strong></p>
                <div class="mt-3 flex items-center gap-2">
                    <a href="#" aria-label="Facebook" class="nf-drawer__social"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M13 22v-8h2.6l.4-3H13V9c0-.9.3-1.5 1.6-1.5H16V5c-.3 0-1.3-.1-2.3-.1-2.3 0-3.7 1.3-3.7 3.8V11H8v3h2v8h3Z"/></svg></a>
                    <a href="#" aria-label="Instagram" class="nf-drawer__social"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
                    <a href="#" aria-label="TikTok" class="nf-drawer__social"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M16 3a5 5 0 0 0 5 5v3a8 8 0 0 1-5-1.8V15a6 6 0 1 1-6-6c.3 0 .7 0 1 .1v3.2A2.8 2.8 0 1 0 13 15V3h3Z"/></svg></a>
                </div>
            </div>
        </div>
    </nav>
</header>
