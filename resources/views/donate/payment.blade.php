@extends('layouts.app')

@section('title', 'Payments — ' . config('app.name'))

@section('content')

    {{-- ===================== TITLE BAND ===================== --}}
    <section class="bg-gray-50">
        <div class="nf-container py-10 text-center sm:py-14">
            <h1 class="text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl">Payments</h1>
        </div>
    </section>

    {{-- ===================== PAYMENT CARD ===================== --}}
    <section class="py-10 sm:py-14">
        <div class="nf-container">
            <div class="nf-reveal mx-auto max-w-3xl rounded-2xl bg-gray-50 p-6 shadow-sm ring-1 ring-black/5 sm:p-8 lg:p-10">

                {{-- ===== Summary ===== --}}
                <h2 class="border-b border-navy/15 pb-3 text-xl font-bold text-navy sm:text-2xl">Payment Summary</h2>

                <dl class="mt-1">
                    @foreach ($donation['items'] as $item)
                        <div class="flex items-center justify-between border-b border-navy/10 py-3">
                            <dt class="text-sm font-bold text-navy-dark">
                                {{ $item['cause'] }}
                                @if ($item['qty'] > 1)
                                    <span class="font-normal text-gray-500">&times; {{ $item['qty'] }}</span>
                                @endif
                            </dt>
                            <dd class="text-sm font-semibold text-navy-dark">
                                £{{ number_format($item['amount'] * $item['qty'], 2) }}
                            </dd>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-between border-b border-navy/10 py-3">
                        <dt class="text-sm font-bold text-navy-dark">Fees</dt>
                        <dd class="text-sm font-semibold text-navy-dark">£{{ number_format($donation['fee'], 2) }}</dd>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <dt class="text-base font-bold text-navy-dark">Total</dt>
                        <dd class="text-base font-extrabold text-brand">£{{ number_format($donation['total'], 2) }}</dd>
                    </div>
                </dl>

                <p class="mt-1 text-xs text-gray-500">Reference: <span class="font-semibold text-navy-dark">{{ $donation['reference'] }}</span></p>

                {{-- ===== Card details ===== --}}
                <h2 class="mt-9 text-xl font-bold text-navy sm:text-2xl">Add Payment Details</h2>

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

                    {{-- Name on card --}}
                    <div class="max-w-sm">
                        <label for="card_name" class="mb-1.5 block text-xs font-bold text-navy">Name on card</label>
                        <input id="card_name" type="text" name="card_name" value="{{ old('card_name') }}"
                               placeholder="Enter Your Name" autocomplete="cc-name" required class="nf-pay-input">
                        @error('card_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Card number --}}
                    <div class="mt-5 max-w-sm">
                        <label for="card_number" class="mb-1.5 block text-xs font-bold text-navy">Card number</label>
                        <input id="card_number" type="text" name="card_number" inputmode="numeric"
                               placeholder="Enter the card number" maxlength="23" autocomplete="cc-number"
                               required data-card-number class="nf-pay-input">
                        @error('card_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Expiry --}}
                    <div class="mt-5">
                        <span class="mb-1.5 block text-xs font-bold text-navy">Expiry Date</span>
                        <div class="grid max-w-sm grid-cols-2 gap-4">
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
                    <div class="mt-5 w-40">
                        <label for="cvc" class="mb-1.5 block text-xs font-bold text-navy">Card Security Code</label>
                        <input id="cvc" type="text" name="cvc" inputmode="numeric" maxlength="4"
                               placeholder="CVC" autocomplete="cc-csc" required data-cvc class="nf-pay-input">
                        @error('cvc') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('donate.checkout') }}"
                           class="inline-flex items-center justify-center rounded-md bg-brand px-6 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-brand-dark hover:shadow-lg">
                            Cancel
                        </a>

                        <button type="submit" data-payment-submit
                                class="inline-flex items-center justify-center gap-2 rounded-md bg-navy px-6 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-navy-dark hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-70">
                            <svg data-payment-spinner class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"/>
                            </svg>
                            <span data-payment-label>Complete Your Donation</span>
                        </button>
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
    </section>

@endsection
