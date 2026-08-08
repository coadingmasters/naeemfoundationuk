@php
    // Only real, existing pages are linked here (dead "#" placeholders removed).
    $appealLinks = [
        ['label' => 'Zakat', 'url' => route('zakat')],
        ['label' => 'Sadaqah', 'url' => route('sadaqah')],
        ['label' => 'Food & Sustenance', 'url' => route('food-sustenance')],
        ['label' => 'Water Well', 'url' => route('water-well')],
        ['label' => 'Healthcare', 'url' => route('healthcare')],
    ];
    $usefulLinks = [
        ['label' => 'About Us', 'url' => route('about')],
        ['label' => 'Shop', 'url' => route('shop')],
        ['label' => 'Community Centre', 'url' => route('community-centre')],
        ['label' => 'Hajj 2027', 'url' => route('hajj')],
        ['label' => 'Ask a Mufti', 'url' => route('ask-mufti')],
        ['label' => 'Volunteer', 'url' => route('volunteer')],
        ['label' => 'Annual Report', 'url' => route('annual-report')],
        ['label' => 'Careers', 'url' => route('careers')],
        ['label' => 'Contact Us', 'url' => route('contact')],
        ['label' => 'Privacy Policy', 'url' => route('privacy-policy')],
    ];

    $bankDetails = [
        'Account Details' => 'Lloyds Bank',
        'Account Name' => 'Naeem Foundation',
        'Account Number' => '13783063',
        'Sort Code' => '30-54-66',
    ];
@endphp

<footer class="bg-brand text-white">
    <div class="nf-container grid grid-cols-2 gap-x-8 gap-y-10 py-14 lg:grid-cols-12">

        {{-- Brand + contact --}}
        <div class="col-span-2 lg:col-span-4">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}"
                 class="mb-4 h-20 w-20 rounded-full bg-white/95 p-1.5 object-contain">

            <p class="mb-5 max-w-xs text-sm leading-relaxed text-white/85">
                Naeem Foundation is a vibrant and compassionate Non-Governmental organization (NGO).
            </p>

            <div class="space-y-2 text-sm text-white/85">
                <p class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24 11.36 11.36 0 0 0 3.57.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.36 11.36 0 0 0 .57 3.57 1 1 0 0 1-.24 1.02l-2.21 2.2Z"/></svg>
                    +44 7960185682 : +44 7960185682
                </p>
                <p class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5-8-5V6l8 5 8-5v2Z"/></svg>
                    donate@naeemfoundation.co.uk
                </p>
            </div>

            {{-- Social icons come from config/social.php — see the socials() helper. --}}
            @if (socials())
                <div class="mt-5 flex gap-3">
                    @foreach (socials() as $s)
                        <a href="{{ $s['url'] }}" aria-label="{{ $s['name'] }}" target="_blank" rel="noopener noreferrer"
                           class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition-colors hover:bg-white/25">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="{{ $s['fill'] }}" stroke="{{ $s['stroke'] }}" stroke-width="2">{!! $s['icon'] !!}</svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Appeals --}}
        <div class="lg:col-span-2">
            <h4 class="mb-4 text-lg font-bold text-white">Appeals</h4>
            <ul class="space-y-2 text-sm">
                @foreach ($appealLinks as $link)
                    <li><a href="{{ $link['url'] }}" class="text-white/85 transition-colors hover:text-white">{{ $link['label'] }}</a></li>
                @endforeach
            </ul>
        </div>

        {{-- Useful Links --}}
        <div class="lg:col-span-2">
            <h4 class="mb-4 text-lg font-bold text-white">Useful Links</h4>
            <ul class="space-y-2 text-sm">
                @foreach ($usefulLinks as $link)
                    <li><a href="{{ $link['url'] }}" class="text-white/85 transition-colors hover:text-white">{{ $link['label'] }}</a></li>
                @endforeach
            </ul>
        </div>

        {{-- Make a Donation --}}
        <div class="col-span-2 lg:col-span-4">
            <h4 class="mb-4 text-lg font-bold text-white">Make a Donation</h4>
            <div class="space-y-3 rounded-lg bg-white p-5 text-sm text-navy-dark">
                @foreach ($bankDetails as $label => $value)
                    <p><span class="font-semibold">{{ $label }} :</span> {{ $value }}</p>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-white/15">
        <div class="nf-container flex flex-col items-center justify-between gap-4 py-5 sm:flex-row">
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-sm text-white/85">
                <p>&copy;NaeemFoundation. All rights reserved.</p>
                <button type="button" data-cookie-open class="underline transition-colors hover:text-white">Cookie Settings</button>
            </div>

            {{-- Fundraising Regulator badge --}}
            <div class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-1.5 text-navy-dark">
                <span class="grid h-8 w-8 place-items-center rounded-full bg-brand text-[11px] font-bold text-white">FR</span>
                <span class="text-[8px] font-semibold uppercase leading-tight tracking-wide">
                    Registered with<br>Fundraising Regulator
                </span>
            </div>
        </div>
    </div>
</footer>
