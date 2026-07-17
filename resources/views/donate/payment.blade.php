@extends('layouts.app')

@section('title', 'Payments — ' . config('app.name'))

{{-- No hero image here, so the header must be solid rather than
     transparent — otherwise this page's content sits under it. --}}
@section('header-solid', 'yes')

@section('content')

    {{-- ===================== TITLE BAND ===================== --}}
    <section class="bg-gray-50">
        <div class="nf-container py-10 text-center sm:py-14">
            <h1 class="text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl">Payment</h1>
            <p class="mt-2 text-sm text-gray-500">Secure checkout — you're one step away from making a difference.</p>
        </div>
    </section>

    {{-- ===================== TWO-COLUMN CHECKOUT ===================== --}}
    <section class="py-10 sm:py-14">
        <div class="nf-container">
            <div class="mx-auto grid max-w-6xl items-start gap-6 lg:grid-cols-5 lg:gap-8">

                {{-- ==================== LEFT: order summary ==================== --}}
                <aside class="nf-reveal lg:col-span-2">
                    <div class="rounded-2xl bg-gray-50 p-6 shadow-sm ring-1 ring-black/5 sm:p-7 lg:sticky lg:top-28">
                        <h2 class="border-b border-navy/15 pb-3 text-lg font-bold text-navy sm:text-xl">Order Summary</h2>

                        <dl class="mt-1" data-summary data-subtotal="{{ $subtotal }}" data-fee="{{ $feeAmount }}">
                            @foreach ($items as $item)
                                <div class="flex items-center justify-between border-b border-navy/10 py-3">
                                    <dt class="pr-3 text-sm font-bold text-navy-dark">
                                        {{ $item['cause'] }}
                                        @if ($item['qty'] > 1)
                                            <span class="font-normal text-gray-500">&times; {{ $item['qty'] }}</span>
                                        @endif
                                    </dt>
                                    <dd class="shrink-0 text-sm font-semibold text-navy-dark">
                                        £{{ number_format($item['amount'] * $item['qty'], 2) }}
                                    </dd>
                                </div>
                            @endforeach

                            <div class="flex items-center justify-between border-b border-navy/10 py-3">
                                <dt class="text-sm text-gray-500">Subtotal</dt>
                                <dd class="text-sm font-semibold text-navy-dark">£{{ number_format($subtotal, 2) }}</dd>
                            </div>

                            <div class="flex items-center justify-between border-b border-navy/10 py-3">
                                <dt class="text-sm text-gray-500">Transaction fee</dt>
                                <dd class="text-sm font-semibold text-navy-dark" data-fee-line>£{{ number_format($coverFee ? $feeAmount : 0, 2) }}</dd>
                            </div>

                            <div class="flex items-center justify-between py-3">
                                <dt class="text-base font-bold text-navy-dark">Total</dt>
                                <dd class="text-lg font-extrabold text-brand" data-total-line>£{{ number_format($total, 2) }}</dd>
                            </div>
                        </dl>

                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="text-xs text-gray-500">Ref: <span class="font-semibold text-navy-dark">{{ $reference }}</span></p>
                            <a href="{{ route('donate.checkout') }}" class="text-xs font-semibold text-brand underline hover:text-navy">
                                Edit your basket
                            </a>
                        </div>

                        {{-- Add-ons live behind a popup --}}
                        <div class="mt-6 border-t border-navy/10 pt-5">
                            <button type="button" data-addons-open
                                    class="flex w-full items-center justify-center gap-2 rounded-lg border border-dashed border-brand/50 px-4 py-3 text-sm font-bold text-brand transition-colors hover:bg-brand/5">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                                Add more to your donation
                            </button>
                        </div>
                    </div>
                </aside>

                {{-- ===== Add-ons popup ===== --}}
                <div class="nf-modal" data-addons-modal @if (session('addons_open')) data-open @endif hidden>
                    <div class="nf-modal__backdrop" data-addons-close></div>
                    <div class="nf-modal__card" role="dialog" aria-modal="true" aria-label="Add to your donation">
                        <button type="button" class="nf-modal__close" data-addons-close aria-label="Close">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                        </button>

                        <h3 class="text-lg font-bold text-navy-dark sm:text-xl">Want to add these?</h3>
                        <p class="mt-1 text-sm text-gray-500">Boost your impact — add an extra gift to your basket.</p>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            @foreach ($addons as $addon)
                                <form method="POST" action="{{ route('donate.add') }}" data-cart-skip>
                                    @csrf
                                    <input type="hidden" name="cause" value="{{ $addon['cause'] }}">
                                    <input type="hidden" name="amount" value="{{ $addon['amount'] }}">
                                    <input type="hidden" name="frequency" value="one-off">
                                    <input type="hidden" name="redirect" value="payment">
                                    <button type="submit" class="nf-addon">
                                        <span class="nf-addon__price">£{{ $addon['amount'] }}</span>
                                        <span class="nf-addon__label">{{ $addon['cause'] }}</span>
                                        <span class="nf-addon__add">Add +</span>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ==================== RIGHT: payment form ==================== --}}
                <div class="nf-reveal lg:col-span-3" style="transition-delay: 0.1s">
                    <div class="rounded-2xl bg-gray-50 p-6 shadow-sm ring-1 ring-black/5 sm:p-7 lg:p-8">
                        <h2 class="text-lg font-bold text-navy sm:text-xl">Add Payment Details</h2>

                        @if ($errors->any())
                            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <ul class="list-inside list-disc space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('donate.payment.process') }}" class="mt-5" data-payment-form autocomplete="off">
                            @csrf
                            <input type="hidden" name="cover_fee_present" value="1">

                            {{-- Cover the transaction fee --}}
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-navy/15 bg-cream/60 p-4">
                                <input type="checkbox" name="cover_fee" value="1" data-cover-fee @checked($coverFee)
                                       class="mt-0.5 h-5 w-5 shrink-0 rounded border-navy/30 text-brand focus:ring-2 focus:ring-brand/30">
                                <span class="text-xs leading-relaxed text-gray-600 sm:text-sm">
                                    We are charged a small fee of 1.4% on every transaction by our payment provider. Would you
                                    like to cover the transaction fee of
                                    <span class="font-semibold text-navy-dark">£{{ number_format($feeAmount, 2) }}</span>
                                    so that we receive your full donation?
                                </span>
                            </label>

                            {{-- Name on card --}}
                            <div class="mt-6">
                                <label for="card_name" class="mb-1.5 block text-xs font-bold text-navy">Name on card</label>
                                <input id="card_name" type="text" name="card_name" value="{{ old('card_name') }}"
                                       placeholder="Enter your name" autocomplete="cc-name" required class="nf-pay-input">
                                @error('card_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Card number --}}
                            <div class="mt-5">
                                <label for="card_number" class="mb-1.5 block text-xs font-bold text-navy">Card number</label>
                                <input id="card_number" type="text" name="card_number" inputmode="numeric"
                                       placeholder="1234 5678 9012 3456" maxlength="23" autocomplete="cc-number"
                                       required data-card-number class="nf-pay-input">
                                @error('card_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Expiry + CVC on one row --}}
                            <div class="mt-5 grid gap-4 sm:grid-cols-3">
                                <div class="sm:col-span-2">
                                    <span class="mb-1.5 block text-xs font-bold text-navy">Expiry date</span>
                                    <div class="grid grid-cols-2 gap-3">
                                        {{-- Month --}}
                                        <div class="nf-cselect h-11 rounded-md border border-navy/20 bg-white" data-cselect>
                                            <button type="button" class="nf-cselect__btn {{ old('expiry_month') ? '' : 'nf-cselect__btn--placeholder' }}"
                                                    data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                                                <span data-cselect-label>{{ old('expiry_month') ? $months[(int) old('expiry_month')] : 'Month' }}</span>
                                                <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                            <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                                                @foreach ($months as $num => $label)
                                                    <li class="nf-cselect__opt {{ (int) old('expiry_month') === $num ? 'is-selected' : '' }}" role="option" data-value="{{ $num }}">{{ $label }}</li>
                                                @endforeach
                                            </ul>
                                            <input type="hidden" name="expiry_month" data-cselect-input value="{{ old('expiry_month') }}">
                                        </div>

                                        {{-- Year --}}
                                        <div class="nf-cselect h-11 rounded-md border border-navy/20 bg-white" data-cselect>
                                            <button type="button" class="nf-cselect__btn {{ old('expiry_year') ? '' : 'nf-cselect__btn--placeholder' }}"
                                                    data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                                                <span data-cselect-label>{{ old('expiry_year') ?: 'Year' }}</span>
                                                <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                            <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                                                @foreach ($years as $year)
                                                    <li class="nf-cselect__opt {{ (int) old('expiry_year') === $year ? 'is-selected' : '' }}" role="option" data-value="{{ $year }}">{{ $year }}</li>
                                                @endforeach
                                            </ul>
                                            <input type="hidden" name="expiry_year" data-cselect-input value="{{ old('expiry_year') }}">
                                        </div>
                                    </div>
                                    @error('expiry_month') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    @error('expiry_year') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- CVC --}}
                                <div>
                                    <label for="cvc" class="mb-1.5 block text-xs font-bold text-navy">Security code</label>
                                    <input id="cvc" type="text" name="cvc" inputmode="numeric" maxlength="4"
                                           placeholder="CVC" autocomplete="cc-csc" required data-cvc class="nf-pay-input">
                                    @error('cvc') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-8 flex flex-col gap-3 sm:flex-row-reverse sm:items-center">
                                <button type="submit" data-payment-submit
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-md bg-navy px-6 py-3 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-navy-dark hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-70 sm:flex-none">
                                    <svg data-payment-spinner class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"/>
                                    </svg>
                                    <span data-payment-label>Complete Your Donation</span>
                                </button>

                                <a href="{{ route('donate.checkout') }}"
                                   class="inline-flex items-center justify-center rounded-md border border-navy/20 px-6 py-3 text-sm font-semibold text-navy transition-colors hover:bg-navy hover:text-white">
                                    Cancel
                                </a>
                            </div>

                            <p class="mt-5 flex items-center gap-2 text-xs text-gray-500">
                                <svg class="h-4 w-4 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3l7 4v5c0 4.4-3 8.4-7 9-4-.6-7-4.6-7-9V7l7-4z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Your payment is encrypted and secure. We never store your card details.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
