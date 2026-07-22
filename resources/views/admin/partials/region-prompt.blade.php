{{-- Shown when a super admin tries to add content while viewing "all regions".
     Lets them pick the region right here, then continues to the create page. --}}
@if (session('region_prompt'))
    @php $to = session('region_prompt'); @endphp
    <div class="nf-modal" data-region-prompt hidden>
        <div class="nf-modal__backdrop" data-region-prompt-close></div>
        <div class="nf-modal__card text-center" role="dialog" aria-modal="true" aria-label="Choose a region">
            <button type="button" class="nf-modal__close" data-region-prompt-close aria-label="Close">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
            </button>

            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-brand/10 text-2xl">🌍</span>
            <h3 class="mt-4 text-lg font-bold text-navy-dark">Choose a region to add content</h3>
            <p class="mt-1.5 text-sm text-gray-500">You’re viewing <strong>all regions</strong>. Pick which region this content belongs to and we’ll take you straight there.</p>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                @foreach ($regions as $r)
                    <a href="{{ route('admin.region.switch', $r['code']) }}?to={{ urlencode($to) }}"
                       class="group rounded-xl border-2 border-gray-200 p-4 transition hover:-translate-y-1 hover:border-brand hover:shadow-lg hover:shadow-brand/10">
                        <span class="block text-3xl leading-none transition group-hover:scale-110">{{ $r['flag'] }}</span>
                        <span class="mt-2 block font-bold text-navy-dark">{{ $r['short'] }}</span>
                        <span class="block text-xs font-semibold text-gray-400">{{ $r['symbol'] }} {{ $r['currency'] }}</span>
                    </a>
                @endforeach
            </div>

            <p class="mt-5 text-xs text-gray-400">Tip: you can also switch region anytime from the top bar.</p>
        </div>
    </div>

    <script>
        (function () {
            const m = document.querySelector('[data-region-prompt]');
            if (!m) return;
            const close = () => { m.classList.remove('is-open'); setTimeout(() => m.remove(), 320); };
            m.querySelectorAll('[data-region-prompt-close]').forEach((el) => el.addEventListener('click', close));
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !m.hidden) close(); });
            m.hidden = false;
            requestAnimationFrame(() => m.classList.add('is-open'));
        })();
    </script>
@endif
