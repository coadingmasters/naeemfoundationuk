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
        'Account Details' => 'Metro Bank',
        'Account Name' => 'Naeem Foundation',
        'Account Number' => '46502817',
        'Sort Code' => '230580',
        'IBAN Number' => 'GB80MYMB23058046502817',
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
                    Contact@naeemfoundation.co.uk
                </p>
            </div>

            <div class="mt-5 flex gap-3">
                {{-- Facebook --}}
                <a href="#" aria-label="Facebook" class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition-colors hover:bg-white/25">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13 22v-8h2.6l.4-3H13V9c0-.9.3-1.5 1.6-1.5H16V5c-.3 0-1.3-.1-2.3-.1-2.3 0-3.7 1.3-3.7 3.8V11H8v3h2v8h3Z"/></svg>
                </a>
                {{-- Instagram --}}
                <a href="#" aria-label="Instagram" class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition-colors hover:bg-white/25">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                </a>
                {{-- X / Twitter --}}
                <a href="#" aria-label="X" class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition-colors hover:bg-white/25">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 3h3l-7 8 8.2 10h-6.4l-5-6.1L8 21H5l7.4-8.5L4.5 3h6.5l4.5 5.6L17.5 3Z"/></svg>
                </a>
            </div>
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
