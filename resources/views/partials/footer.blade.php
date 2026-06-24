@php
    $footerLinks = [
        'Quick Links' => ['About Us', 'Projects', 'Appeals', 'Community Center', 'Volunteer'],
        'Donate' => ['Give Zakat', 'Give Sadaqah', 'Support an Orphan', 'Water Pump', 'Ramadan 2026'],
    ];
@endphp

<footer class="bg-navy-dark text-white/80">
    <div class="nf-container grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Brand --}}
        <div>
            <div class="mb-4">
                <span class="inline-grid h-20 w-20 place-items-center rounded-full bg-white p-1.5">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-full w-full object-contain">
                </span>
            </div>
            <p class="text-sm leading-relaxed">
                Changing lives through Zakat, Sadaqah and sustainable relief projects across communities in need.
            </p>
        </div>

        {{-- Link columns --}}
        @foreach ($footerLinks as $heading => $links)
            <div>
                <h4 class="mb-4 text-sm font-bold uppercase tracking-wide text-white">{{ $heading }}</h4>
                <ul class="space-y-2 text-sm">
                    @foreach ($links as $link)
                        <li><a href="#" class="transition-colors hover:text-white">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        {{-- Contact --}}
        <div>
            <h4 class="mb-4 text-sm font-bold uppercase tracking-wide text-white">Get in Touch</h4>
            <ul class="space-y-2 text-sm">
                <li>info@naeemfoundation.org</li>
                <li>+92 300 0000000</li>
                <li>Karachi, Pakistan</li>
            </ul>
            <div class="mt-4 flex gap-3">
                @foreach (['facebook', 'twitter', 'instagram'] as $social)
                    <a href="#" aria-label="{{ $social }}"
                       class="grid h-9 w-9 place-items-center rounded-full bg-white/10 transition-colors hover:bg-brand">
                        <span class="text-xs uppercase">{{ substr($social, 0, 1) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="nf-container flex flex-col items-center justify-between gap-2 py-5 text-xs sm:flex-row">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Privacy Policy &middot; Terms of Service</p>
        </div>
    </div>
</footer>
