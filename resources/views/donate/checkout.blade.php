@extends('layouts.app')

@section('title', 'Complete Contribution — ' . config('app.name'))

{{-- No hero image here, so the header must be solid rather than
     transparent — otherwise this page's content sits under it. --}}
@section('header-solid', 'yes')

@section('content')

    <section class="bg-cream py-10 sm:py-14">
        <div class="nf-container">
            <div class="mx-auto max-w-4xl overflow-hidden rounded-2xl bg-navy px-5 py-8 shadow-xl sm:px-8 sm:py-10 lg:px-12">

                <h1 class="text-center text-2xl font-extrabold text-white sm:text-3xl lg:text-4xl">
                    Complete Contribution
                </h1>

                {{-- ===== Flash / errors ===== --}}
                @if (session('success'))
                    <p class="mt-5 rounded-lg bg-white/10 px-4 py-3 text-center text-sm text-white ring-1 ring-white/20">
                        {{ session('success') }}
                    </p>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-lg bg-red-500/15 px-4 py-3 text-sm text-white ring-1 ring-red-300/40">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ===== Basket ===== --}}
                @if (empty($items))
                    <div class="mt-8 rounded-xl bg-white p-8 text-center">
                        <p class="text-sm text-gray-600">Your contribution is empty.</p>
                        <a href="{{ route('home') }}" class="btn-brand mt-4">Browse causes</a>
                    </div>
                @else
                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        @foreach ($items as $item)
                            <div class="flex items-center gap-3 rounded-lg bg-white p-3">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['cause'] }}"
                                     class="h-16 w-16 shrink-0 rounded object-cover">

                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-base font-bold text-navy-dark">{{ $item['cause'] }}</h3>
                                    <p class="text-xs text-gray-500">
                                        {{ money($item['amount']) }} each
                                        @if (($item['frequency'] ?? 'one-off') === 'monthly')
                                            <span class="font-semibold text-brand">/ monthly</span>
                                        @endif
                                    </p>

                                    <div class="mt-1.5 flex items-center gap-3">
                                        @include('partials.cart-stepper', ['item' => $item])
                                        <span class="text-sm font-bold text-navy-dark">
                                            {{ money($item['amount'] * $item['qty']) }}
                                        </span>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('donate.remove', $item['id']) }}" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded bg-brand px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-brand-dark">
                                        remove
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    {{-- ===== Total ===== --}}
                    <div class="mt-4 flex items-center justify-between rounded-lg bg-white px-5 py-4">
                        <span class="text-lg font-bold text-navy-dark">Total</span>
                        <span class="text-lg font-bold text-navy-dark">{{ money($subtotal) }}</span>
                    </div>
                @endif

                {{-- ===== Details form ===== --}}
                @php $d = $details ?? []; @endphp
                <form method="POST" action="{{ route('donate.store') }}" class="mt-10">
                    @csrf

                    <h2 class="text-xl font-bold text-white sm:text-2xl">Enter Your Details</h2>

                    {{-- Gift Aid — a UK-only tax relief, so it's hidden outside the UK. --}}
                    @if (region('code') === 'GB')
                        <h3 class="mt-6 text-lg font-semibold italic text-white/90">Gift Aid</h3>
                        <label class="mt-2 flex cursor-pointer items-start gap-3">
                            <input type="checkbox" name="gift_aid" value="1" @checked(old('gift_aid', $d['gift_aid'] ?? false))
                                   class="mt-0.5 h-5 w-5 shrink-0 rounded border-white/40 bg-white/10 text-brand focus:ring-2 focus:ring-white/40">
                            <span class="text-xs leading-relaxed text-white/85 sm:text-sm">
                                I am a UK taxpayer, donating as an individual and would like Naeem Foundation to claim Gift Aid
                                on my donation <span class="text-brand">*</span>
                            </span>
                        </label>
                    @endif

                    {{-- Name --}}
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="first_name" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*First Name</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $d['first_name'] ?? '') }}" required
                                   class="nf-dark-input">
                            @error('first_name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="last_name" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Last Name</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $d['last_name'] ?? '') }}" required
                                   class="nf-dark-input">
                            @error('last_name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $d['email'] ?? '') }}" required
                                   class="nf-dark-input">
                            @error('email') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Contact Number</label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone', $d['phone'] ?? '') }}" required
                                   class="nf-dark-input">
                            @error('phone') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- On behalf of org (reveals the organisation name) --}}
                    <label class="mt-6 flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="on_behalf_of_organisation" value="1" data-org-toggle
                               @checked(old('on_behalf_of_organisation', $d['on_behalf_of_organisation'] ?? false))
                               class="h-5 w-5 shrink-0 rounded border-white/40 bg-white/10 text-brand focus:ring-2 focus:ring-white/40">
                        <span class="text-xs text-white/85 sm:text-sm">I am donating on behalf of an organization.</span>
                    </label>

                    {{-- Organisation name — revealed only when the box above is ticked --}}
                    <div data-org-field class="mt-4 {{ old('on_behalf_of_organisation', $d['on_behalf_of_organisation'] ?? false) ? '' : 'hidden' }}">
                        <label for="organisation_name" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Organization Name</label>
                        <input id="organisation_name" type="text" name="organisation_name" value="{{ old('organisation_name', $d['organisation_name'] ?? '') }}"
                               placeholder="Enter your organization's name" class="nf-dark-input">
                        @error('organisation_name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                    </div>

                    @php
                        $postLabel = region('code') === 'US' ? 'ZIP Code' : 'Postcode';
                        $countryName = region('name');

                        // Until a full house-by-house provider is configured, lead with
                        // manual entry: the address fields are visible and required from
                        // the start, and the lookup is an optional shortcut rather than
                        // the only way in. Add GETADDRESS_KEY (and set
                        // ADDRESS_UK_PROVIDER=getaddress) and this flips itself back to
                        // the find-first flow — no template change needed.
                        $manualAddress = ! (config('address.uk_provider') === 'getaddress'
                            && filled(config('address.getaddress_key')));
                    @endphp

                    {{-- Postcode finder: enter a {{ $postLabel }}, search, then pick your
                         address. The fields below auto-fill and stay editable. --}}
                    <div class="mt-6" data-address-finder data-address-region="{{ region('code') }}">
                        <label for="postcode" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">
                            {{ $manualAddress ? '*'.$postLabel : 'Find '.$countryName.' address' }}
                            @unless ($manualAddress) <span class="text-brand">*</span> @endunless
                        </label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input id="postcode" type="text" name="postcode" value="{{ old('postcode', $d['postcode'] ?? '') }}" required
                                       data-address-postcode autocomplete="off" placeholder="Start typing your {{ $postLabel }}…" class="nf-dark-input w-full">
                                {{-- Live postcode suggestions (free, appear as you type) --}}
                                <ul data-address-suggest
                                    class="absolute left-0 right-0 top-full z-30 mt-1 hidden max-h-60 overflow-auto rounded-lg bg-white py-1 text-sm text-navy-dark shadow-2xl ring-1 ring-black/5"></ul>
                            </div>
                            <button type="button" data-address-find
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-white px-4 text-sm font-bold text-navy-dark transition hover:bg-cream disabled:opacity-60">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4" stroke-linecap="round"/></svg>
                                <span data-address-find-label>Find address</span>
                            </button>
                        </div>
                        @error('postcode') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        <p class="mt-1.5 text-xs text-white/60">
                            @if ($manualAddress)
                                Just type your address in the boxes below. &ldquo;Find address&rdquo; is optional &mdash; it fills in your town for you ({{ $countryName }} only).
                            @else
                                The {{ $postLabel }} search works for {{ $countryName }} addresses only. If your billing address is outside {{ $countryName }}, enter it manually below.
                            @endif
                        </p>

                        {{-- Result message (invalid / found) --}}
                        <p data-address-msg class="mt-2 hidden text-xs"></p>

                        {{-- Pointless when the fields are already on screen. --}}
                        @unless ($manualAddress)
                            <button type="button" data-address-manual
                                    class="mt-2 text-xs font-semibold text-white underline underline-offset-2 transition-colors hover:text-cream">
                                Enter address manually
                            </button>
                        @endunless
                    </div>

                    {{-- Address fields. In manual mode they're on screen from the start.
                         Otherwise they stay hidden until a postcode is found (then
                         auto-filled) or "Enter address manually" is clicked — and are
                         always shown on a validation error so nothing is missed. --}}
                    @php
                        $showAddrFields = $manualAddress
                            || $errors->has('billing_address') || $errors->has('city')
                            || old('billing_address', $d['billing_address'] ?? '') !== ''
                            || old('city', $d['city'] ?? '') !== '';
                    @endphp
                    <div data-address-fields class="{{ $showAddrFields ? '' : 'hidden' }}">
                        {{-- Marked required here when they're already visible; the script
                             adds it later if they get revealed. A hidden required field
                             would silently block the submit, which is why it's conditional. --}}
                        {{-- One field, two ways in: pick a found address from the list
                             that drops down here, or just type it. There is no separate
                             "select your address" box. --}}
                        <div class="mt-5">
                            <label for="billing_address" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Billing Address</label>
                            <div class="relative">
                                <input id="billing_address" type="text" name="billing_address" value="{{ old('billing_address', $d['billing_address'] ?? '') }}"
                                       placeholder="House number and street" data-address-line1 autocomplete="street-address"
                                       role="combobox" aria-expanded="false" aria-autocomplete="list" @required($showAddrFields)
                                       class="nf-dark-input w-full">

                                {{-- Chevron — reopens the found-address list. Hidden until
                                     a lookup actually returns street-level addresses. --}}
                                <button type="button" data-address-options-toggle hidden aria-label="Show found addresses"
                                        class="absolute right-2 top-1/2 z-10 hidden -translate-y-1/2 rounded p-1.5 text-white/70 transition-colors hover:text-white">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>

                                <ul data-address-options role="listbox"
                                    class="absolute left-0 right-0 top-full z-30 mt-1 hidden max-h-60 overflow-auto rounded-lg bg-white py-1 text-sm text-navy-dark shadow-2xl ring-1 ring-black/5"></ul>
                            </div>
                            @error('billing_address') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-5">
                            <label for="city" class="mb-1.5 block text-xs font-semibold text-white sm:text-sm">*Town / City</label>
                            <input id="city" type="text" name="city" value="{{ old('city', $d['city'] ?? '') }}"
                                   placeholder="Town or city" data-address-city autocomplete="address-level2"
                                   @required($showAddrFields) class="nf-dark-input">
                            @error('city') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" @disabled(empty($items))
                                class="rounded-lg bg-white px-6 py-3 text-sm font-bold text-navy-dark transition-colors hover:bg-cream disabled:cursor-not-allowed disabled:opacity-50">
                            Add Payment Details
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>

@endsection
