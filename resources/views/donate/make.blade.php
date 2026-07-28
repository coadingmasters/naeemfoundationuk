@extends('layouts.app')

@section('title', 'Make a Donation — ' . config('app.name'))

{{-- No image behind the header on this flow — keep it solid. --}}
@section('header-solid', 'yes')

@section('content')
@php
    // Funds the donor can direct their gift to. "Where Most Needed" leads.
    $funds = array_merge(
        [['title' => 'Where Most Needed']],
        config('giving.appeals.items', []),
        config('giving.islamic.items', []),
    );
    $tick = '<span class="nf-wz-opt__tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="h-3 w-3"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
    $arrow = '<svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
@endphp

<div data-donate-wizard>

    {{-- ================= SCREEN 1 · START ================= --}}
    <section data-screen="start" class="nf-wz-screen">
        <div class="grid lg:grid-cols-2">
            {{-- Left: dark panel with the intro + One-off / Monthly --}}
            <div class="relative flex items-center bg-navy-dark px-6 py-14 sm:px-10 lg:px-14 lg:py-24">
                <div class="w-full max-w-lg">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white ring-1 ring-white/15">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand"></span> Naeem Foundation
                    </span>

                    <h1 class="mt-5 text-4xl font-extrabold italic leading-[1.05] text-white sm:text-5xl">
                        Make a Donation
                    </h1>
                    <p class="mt-4 text-base leading-relaxed text-white/80 sm:text-lg">
                        Be a lifesaver. Make your donation to Naeem Foundation today.
                    </p>

                    <div class="mt-8 grid max-w-md grid-cols-2 gap-4">
                        <button type="button" data-freq="one-off" class="nf-wz-cta">
                            One off {!! $arrow !!}
                        </button>
                        <button type="button" data-freq="monthly" class="nf-wz-cta">
                            Monthly {!! $arrow !!}
                        </button>
                    </div>

                    <button type="button" data-friday
                            class="group mt-7 inline-flex items-center gap-2 text-lg font-bold text-white transition-colors hover:text-brand">
                        Schedule your Regular Giving
                        <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>

            {{-- Right: image --}}
            <div class="relative min-h-[300px] lg:min-h-[620px]">
                <img src="{{ asset('images/changinslives1.jpg') }}" alt=""
                     class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-navy-dark/40 to-transparent lg:from-navy-dark/25"></div>
            </div>
        </div>
    </section>

    {{-- ================= SCREEN 2+ · WIZARD ================= --}}
    <section data-screen="steps" hidden class="nf-wz-screen bg-cream py-10 sm:py-14">
        <div class="nf-container">

            {{-- Progress --}}
            <ol class="nf-wz-steps mb-9 sm:mb-11">
                @foreach (['Start', 'Donation', 'Gift Aid', 'Keep in touch', 'Details', 'Payment'] as $i => $label)
                    <li class="nf-wz-step {{ $i === 0 ? 'is-done' : '' }} {{ $i === 1 ? 'is-active' : '' }}" data-step="{{ $i + 1 }}">
                        <span class="nf-wz-step__num">{{ $i + 1 }}</span>
                        <span class="nf-wz-step__label">{{ $label }}</span>
                    </li>
                @endforeach
            </ol>

            <div class="mx-auto max-w-3xl rounded-2xl bg-white p-6 shadow-xl shadow-navy/10 sm:p-8 lg:p-10">
                <div data-flash hidden class="mb-5 rounded-lg bg-brand/10 px-4 py-2.5 text-center text-sm font-semibold text-brand"></div>

                {{-- ===== STEP 2 · Donation (fund + amount) ===== --}}
                <div class="nf-wz-panel is-in" data-panel="2">
                    <h2 class="text-2xl font-extrabold text-navy-dark sm:text-3xl">Select your fund</h2>
                    <p class="mt-1.5 text-sm text-navy/70">Choose where you&rsquo;d like your <span data-freq-label class="font-semibold text-brand">one-off</span> gift to go.</p>

                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @foreach ($funds as $f)
                            <button type="button" data-fund="{{ $f['title'] }}" class="nf-wz-opt">
                                {{ $f['title'] }}{!! $tick !!}
                            </button>
                        @endforeach
                    </div>

                    {{-- Amount — revealed once a fund is chosen --}}
                    <div data-amount-block hidden class="nf-wz-reveal mt-8 border-t border-navy/10 pt-7">
                        <h3 class="text-lg font-bold text-navy-dark">Choose an amount</h3>
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <button type="button" data-amount="100" class="nf-choice py-3 text-base">{{ region('symbol') }}100</button>
                            <button type="button" data-amount="200" class="nf-choice py-3 text-base">{{ region('symbol') }}200</button>
                            <button type="button" data-amount="300" class="nf-choice py-3 text-base">{{ region('symbol') }}300</button>
                            <button type="button" data-amount="other" class="nf-choice py-3 text-base">Other</button>
                        </div>

                        <div data-other-wrap hidden class="mt-4">
                            <label class="mb-1.5 block text-sm font-semibold text-navy-dark">Enter your amount ({{ region('symbol') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-bold text-navy/50">{{ region('symbol') }}</span>
                                <input type="number" min="1" step="0.01" placeholder="e.g. 75" data-other-input
                                       class="h-12 w-full rounded-md border border-gray-300 pl-7 pr-3 text-base text-navy-dark focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col-reverse items-stretch gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <button type="button" data-back-start class="nf-wz-nav-back">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Back
                        </button>
                        <button type="button" data-next="3" class="btn-brand px-7 py-3 text-base">
                            Continue {!! $arrow !!}
                        </button>
                    </div>
                </div>

                {{-- ===== STEP 3 · Gift Aid ===== --}}
                <div class="nf-wz-panel" data-panel="3" hidden>
                    <h2 class="text-2xl font-extrabold text-navy-dark sm:text-3xl">Boost your donation with Gift Aid</h2>
                    <p class="mt-1.5 text-sm text-navy/70">If you&rsquo;re a UK taxpayer, add 25% at no extra cost to you.</p>

                    <div class="mt-6 rounded-xl bg-cream/70 p-6 text-center ring-1 ring-navy/10">
                        <p class="text-sm font-semibold uppercase tracking-wide text-brand">Your gift could become</p>
                        <p class="mt-2 flex items-center justify-center gap-3 text-3xl font-extrabold text-navy-dark sm:text-4xl">
                            <span data-ga-amount>{{ region('symbol') }}0.00</span>
                            <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-brand" data-ga-plus>{{ region('symbol') }}0.00</span>
                        </p>
                    </div>

                    <label class="mt-6 flex cursor-pointer items-start gap-3 rounded-xl border border-navy/10 p-4 transition-colors hover:border-brand">
                        <input type="checkbox" data-ga-check
                               class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-brand focus:ring-2 focus:ring-brand/30">
                        <span class="text-sm leading-relaxed text-navy-dark">
                            I am a UK taxpayer and would like Naeem Foundation to claim Gift Aid on this donation and any
                            I make in the future or have made in the past 4 years.
                        </span>
                    </label>

                    <div class="mt-8 flex flex-col-reverse items-stretch gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <button type="button" data-back="2" class="nf-wz-nav-back">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Back
                        </button>
                        <button type="button" data-next="4" class="btn-brand px-7 py-3 text-base">
                            Continue {!! $arrow !!}
                        </button>
                    </div>
                </div>

                {{-- ===== STEP 4 · Keep in touch ===== --}}
                <div class="nf-wz-panel" data-panel="4" hidden>
                    <h2 class="text-2xl font-extrabold text-navy-dark sm:text-3xl">Keep in touch</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-navy/70">
                        We&rsquo;d love to keep you updated on how your donation is changing lives, and on the
                        appeals that need you most. We&rsquo;ll always treat your details with the utmost care,
                        never share them, and you can opt out at any time.
                    </p>

                    <p class="mt-6 text-sm font-semibold text-brand">I&rsquo;m happy for Naeem Foundation to contact me via:</p>
                    @php
                        $consentOptions = [
                            ['key' => 'email', 'label' => 'Email', 'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6" stroke-linecap="round" stroke-linejoin="round"/>'],
                            ['key' => 'phone', 'label' => 'Telephone', 'icon' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z" stroke-linecap="round" stroke-linejoin="round"/>'],
                            ['key' => 'sms', 'label' => 'SMS', 'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/>'],
                        ];
                    @endphp
                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        @foreach ($consentOptions as $opt)
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-navy/10 p-4 transition-colors hover:border-brand has-[:checked]:border-brand has-[:checked]:bg-brand/5">
                                <input type="checkbox" data-consent="{{ $opt['key'] }}"
                                       class="h-5 w-5 shrink-0 rounded border-gray-300 text-brand focus:ring-2 focus:ring-brand/30">
                                <span class="inline-flex items-center gap-2 text-sm font-semibold text-navy-dark">
                                    <svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $opt['icon'] !!}</svg>
                                    {{ $opt['label'] }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-navy/50">Prefer not to hear from us? Just leave these unticked &mdash; your donation still goes through.</p>

                    <div class="mt-8 flex flex-col-reverse items-stretch gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                        <button type="button" data-back="3" class="nf-wz-nav-back">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Back
                        </button>
                        <button type="button" data-submit class="btn-brand justify-center whitespace-nowrap px-7 py-3 text-base">
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z"/></svg>
                            Donate Now
                        </button>
                    </div>
                </div>
            </div>

            {{-- Reassurance strip --}}
            <div class="mx-auto mt-6 flex max-w-3xl flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs font-semibold text-navy/60">
                <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z"/></svg> 100% Donation Policy</span>
                <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg> Safe &amp; secure payments</span>
                <span class="inline-flex items-center gap-1.5"><svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v5l3 2" stroke-linecap="round"/></svg> Cancel monthly anytime</span>
            </div>
        </div>
    </section>
</div>

{{-- Hidden hand-off: reuses the existing basket flow (add → checkout → payment → thank you). --}}
<form id="wz-handoff" method="POST" action="{{ route('donate.add') }}" class="hidden">
    @csrf
    <input type="hidden" name="cause" id="wz-cause">
    <input type="hidden" name="amount" id="wz-amount">
    <input type="hidden" name="frequency" id="wz-frequency">
    {{-- "Keep in touch" marketing consent, carried through to the donation record. --}}
    <input type="hidden" name="contact_email" id="wz-c-email">
    <input type="hidden" name="contact_phone" id="wz-c-phone">
    <input type="hidden" name="contact_sms" id="wz-c-sms">
    <input type="hidden" name="image" value="images/changinslives1.jpg">
</form>

@push('scripts')
<script>
(function () {
    const root = document.querySelector('[data-donate-wizard]');
    if (!root) return;

    const KEY = 'nf_donation_wizard';
    const load = () => { try { return JSON.parse(localStorage.getItem(KEY)) || {}; } catch (e) { return {}; } };
    const save = () => { try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) {} };
    const state = load();

    const startScreen = root.querySelector('[data-screen="start"]');
    const stepsScreen = root.querySelector('[data-screen="steps"]');
    const steps = Array.from(root.querySelectorAll('.nf-wz-step'));
    const panels = Array.from(root.querySelectorAll('.nf-wz-panel'));
    const freqBtns = Array.from(root.querySelectorAll('[data-freq]'));
    const freqLabel = root.querySelector('[data-freq-label]');
    const fundBtns = Array.from(root.querySelectorAll('[data-fund]'));
    const amountBlock = root.querySelector('[data-amount-block]');
    const amountBtns = Array.from(root.querySelectorAll('[data-amount]'));
    const otherWrap = root.querySelector('[data-other-wrap]');
    const otherInput = root.querySelector('[data-other-input]');
    const gaAmount = root.querySelector('[data-ga-amount]');
    const gaPlus = root.querySelector('[data-ga-plus]');
    const gaCheck = root.querySelector('[data-ga-check]');
    const consentBoxes = Array.from(root.querySelectorAll('[data-consent]'));
    const flashEl = root.querySelector('[data-flash]');

    const money = (n) => (window.NF_CURRENCY || '£') + (Number(n) || 0).toFixed(2);
    let flashTimer;
    function flash(msg) {
        if (!flashEl) return;
        flashEl.textContent = msg;
        flashEl.hidden = false;
        clearTimeout(flashTimer);
        flashTimer = setTimeout(() => { flashEl.hidden = true; }, 3200);
    }

    function reveal(el) {
        el.hidden = false;
        requestAnimationFrame(() => el.classList.add('is-in'));
    }

    // ---- Step navigation within the wizard screen ----
    function showStep(n) {
        panels.forEach((p) => {
            const pn = Number(p.dataset.panel);
            if (pn === n) { p.hidden = false; requestAnimationFrame(() => p.classList.add('is-in')); }
            else { p.classList.remove('is-in'); p.hidden = true; }
        });
        steps.forEach((s) => {
            const sn = Number(s.dataset.step);
            s.classList.toggle('is-active', sn === n);
            s.classList.toggle('is-done', sn < n);
        });
    }

    // ---- Start -> wizard ----
    function enterWizard(freq) {
        state.frequency = freq; save();
        if (freqLabel) freqLabel.textContent = freq === 'monthly' ? 'monthly' : 'one-off';
        freqBtns.forEach((b) => b.classList.toggle('is-selected', b.dataset.freq === freq));
        startScreen.hidden = true;
        stepsScreen.hidden = false;
        showStep(2);
        // If a fund was already chosen (e.g. via a cause "Donate" deep-link),
        // reveal the amount straight away so the donor only picks an amount.
        if (state.fund) reveal(amountBlock);
        stepsScreen.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    freqBtns.forEach((b) => b.addEventListener('click', () => enterWizard(b.dataset.freq)));
    const friday = root.querySelector('[data-friday]');
    if (friday) friday.addEventListener('click', () => enterWizard('monthly'));

    // Back to the start screen
    const backStart = root.querySelector('[data-back-start]');
    if (backStart) backStart.addEventListener('click', () => {
        stepsScreen.hidden = true;
        startScreen.hidden = false;
        startScreen.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    // ---- Fund select -> reveal amount + scroll ----
    function selectFund(btn) {
        state.fund = btn.dataset.fund; save();
        fundBtns.forEach((x) => x.classList.toggle('is-selected', x === btn));
        reveal(amountBlock);
        setTimeout(() => amountBlock.scrollIntoView({ behavior: 'smooth', block: 'center' }), 140);
    }
    fundBtns.forEach((b) => b.addEventListener('click', () => selectFund(b)));

    // ---- Deep-link: a cause "Donate" button pre-selects its fund via ?fund= ----
    (function () {
        const preFund = new URLSearchParams(window.location.search).get('fund');
        if (!preFund) return;

        state.fund = preFund; save();
        let btn = fundBtns.find((b) => b.dataset.fund === preFund);

        // The cause may not be one of the standard funds — add it at the top.
        if (!btn && fundBtns[0]) {
            btn = fundBtns[0].cloneNode(true);
            btn.dataset.fund = preFund;
            btn.childNodes[0].textContent = preFund + ' ';
            fundBtns[0].parentElement.prepend(btn);
            btn.addEventListener('click', () => selectFund(btn));
            fundBtns.unshift(btn);
        }

        if (btn) btn.classList.add('is-selected');
    })();

    // ---- Amount select ----
    amountBtns.forEach((b) => b.addEventListener('click', () => {
        amountBtns.forEach((x) => x.classList.toggle('is-selected', x === b));
        if (b.dataset.amount === 'other') {
            otherWrap.hidden = false;
            otherInput.focus();
            state.amount = parseFloat(otherInput.value) || null;
        } else {
            otherWrap.hidden = true;
            state.amount = parseFloat(b.dataset.amount);
        }
        save();
    }));
    if (otherInput) otherInput.addEventListener('input', () => {
        state.amount = parseFloat(otherInput.value) || null; save();
    });

    // ---- Gift aid figures ----
    function updateGiftAid() {
        const a = Number(state.amount) || 0;
        gaAmount.textContent = money(a);
        gaPlus.textContent = money(a * 1.25);
    }
    // Gift Aid and marketing consent are a fresh, active choice on every
    // donation — never restored from a previous session's localStorage.
    if (gaCheck) {
        state.giftAid = false;
        gaCheck.checked = false;
        gaCheck.addEventListener('change', () => { state.giftAid = gaCheck.checked; save(); });
    }

    // ---- Keep in touch (marketing consent) ----
    state.consent = {};
    consentBoxes.forEach((box) => {
        const key = box.dataset.consent;
        box.checked = false;
        box.addEventListener('change', () => { state.consent[key] = box.checked; save(); });
    });
    save();

    // ---- Next / Back ----
    root.querySelectorAll('[data-next]').forEach((b) => b.addEventListener('click', () => {
        const to = Number(b.dataset.next);
        if (to === 3) {
            if (!state.fund) { flash('Please choose a fund first.'); return; }
            if (!state.amount || state.amount < 1) { flash('Please choose or enter an amount.'); return; }
            updateGiftAid();
        }
        showStep(to);
        stepsScreen.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }));
    root.querySelectorAll('[data-back]').forEach((b) => b.addEventListener('click', () => {
        showStep(Number(b.dataset.back));
        stepsScreen.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }));

    // ---- Final hand-off to the existing checkout ----
    const handoff = document.getElementById('wz-handoff');
    root.querySelectorAll('[data-submit]').forEach((b) => b.addEventListener('click', () => {
        if (!state.fund || !state.amount) { flash('Please complete your donation first.'); return; }
        document.getElementById('wz-cause').value = state.fund;
        document.getElementById('wz-amount').value = state.amount;
        document.getElementById('wz-frequency').value = state.frequency || 'one-off';
        // Marketing consent from the "Keep in touch" step.
        const consent = state.consent || {};
        document.getElementById('wz-c-email').value = consent.email ? 1 : '';
        document.getElementById('wz-c-phone').value = consent.phone ? 1 : '';
        document.getElementById('wz-c-sms').value = consent.sms ? 1 : '';
        // requestSubmit() fires the submit *event*, so the global /donate/add
        // handler in app.js runs — it AJAX-adds the gift and pops the header
        // mini-cart open, just like the widget on every other page. (Plain
        // form.submit() would skip that handler and do a full-page redirect.)
        if (typeof handoff.requestSubmit === 'function') handoff.requestSubmit();
        else handoff.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        // Bring the sticky header (and its popup) into view from deep in the flow.
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }));

    // ---- Restore prior in-session selections (highlights only; start at screen 1) ----
    if (state.fund) fundBtns.forEach((b) => b.classList.toggle('is-selected', b.dataset.fund === state.fund));
    if (state.amount) amountBtns.forEach((b) => b.classList.toggle('is-selected', String(b.dataset.amount) === String(state.amount)));
})();
</script>
@endpush
@endsection
