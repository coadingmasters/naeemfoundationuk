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
                                        {{ money($item['amount'] * $item['qty']) }}
                                    </dd>
                                </div>
                            @endforeach

                            <div class="flex items-center justify-between border-b border-navy/10 py-3">
                                <dt class="text-sm text-gray-500">Subtotal</dt>
                                <dd class="text-sm font-semibold text-navy-dark">{{ money($subtotal) }}</dd>
                            </div>

                            <div class="flex items-center justify-between border-b border-navy/10 py-3">
                                <dt class="text-sm text-gray-500">Transaction fee</dt>
                                <dd class="text-sm font-semibold text-navy-dark" data-fee-line>{{ money($coverFee ? $feeAmount : 0) }}</dd>
                            </div>

                            <div class="flex items-center justify-between py-3">
                                <dt class="text-base font-bold text-navy-dark">Total</dt>
                                <dd class="text-lg font-extrabold text-brand" data-total-line>{{ money($total) }}</dd>
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
                {{-- Always opens on load (app.js reads data-open) so the donor is
                     prompted to boost their gift every time they reach payment. --}}
                <div class="nf-modal" data-addons-modal data-open hidden>
                    <div class="nf-modal__backdrop" data-addons-close></div>
                    <div class="nf-modal__card nf-modal__card--split" role="dialog" aria-modal="true" aria-label="Add to your donation">
                        <button type="button" class="nf-modal__close" data-addons-close aria-label="Close">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                        </button>

                        <div class="grid sm:grid-cols-2">
                            {{-- Left: amounts --}}
                            <div class="p-6 sm:p-7 lg:p-8">
                                <h3 class="text-xl font-extrabold text-navy-dark sm:text-2xl">Add more to your donation</h3>
                                <p class="mt-1.5 text-sm text-gray-500">Boost your impact — add an extra gift before you pay.</p>

                                <div class="mt-5 flex flex-col gap-3">
                                    @foreach ($addons as $addon)
                                        <form method="POST" action="{{ route('donate.add') }}" data-cart-skip>
                                            @csrf
                                            <input type="hidden" name="cause" value="{{ $addon['cause'] }}">
                                            <input type="hidden" name="amount" value="{{ $addon['amount'] }}">
                                            <input type="hidden" name="frequency" value="one-off">
                                            <input type="hidden" name="redirect" value="payment">
                                            <button type="submit" class="nf-addon">
                                                <span class="nf-addon__info">
                                                    <span class="nf-addon__price">{{ region('symbol') }}{{ $addon['amount'] }}</span>
                                                    <span class="nf-addon__label">{{ $addon['cause'] }}</span>
                                                </span>
                                                <span class="nf-addon__add">Add +</span>
                                            </button>
                                        </form>
                                    @endforeach
                                </div>

                                {{-- Dismiss without adding anything. --}}
                                <button type="button" data-addons-close
                                        class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-brand">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                                    No thanks, continue to payment
                                </button>
                            </div>

                            {{-- Right: image --}}
                            <div class="relative hidden min-h-[20rem] sm:block">
                                <img src="{{ asset('images/changinslives2.jpg') }}" alt=""
                                     class="absolute inset-0 h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-dark/60 via-navy-dark/10 to-transparent"></div>
                                <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-white/80">Every gift counts</p>
                                    <p class="mt-1 text-lg font-bold leading-tight">Your kindness changes lives.</p>
                                </div>
                            </div>
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

                        <div class="mt-5">
                            {{-- Cover the transaction fee --}}
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-navy/15 bg-cream/60 p-4">
                                <input type="checkbox" name="cover_fee" value="1" data-cover-fee @checked($coverFee)
                                       class="mt-0.5 h-5 w-5 shrink-0 rounded border-navy/30 text-brand focus:ring-2 focus:ring-brand/30">
                                <span class="text-xs leading-relaxed text-gray-600 sm:text-sm">
                                    We are charged a small fee of 1.4% on every transaction by our payment provider. Would you
                                    like to cover the transaction fee of
                                    <span class="font-semibold text-navy-dark">{{ money($feeAmount) }}</span>
                                    so that we receive your full donation?
                                </span>
                            </label>


                            {{-- PayPal Smart Buttons — PayPal hosts the whole
                                 payment, so card details never touch our server. --}}
                            <div class="mt-7"
                                 data-paypal
                                 data-order-url="{{ route('paypal.donation.order') }}"
                                 data-capture-url="{{ route('paypal.donation.capture') }}">

                                <p class="mb-3 text-xs font-bold text-navy">Choose how to pay</p>

                                <div data-paypal-error
                                     class="mb-3 hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

                                {{-- PayPal renders its buttons in here --}}
                                <div data-paypal-buttons class="min-h-[3rem]"></div>

                                <div data-paypal-busy class="mt-3 hidden items-center justify-center gap-2 text-sm font-semibold text-navy">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"/>
                                    </svg>
                                    Completing your donation, please wait...
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('donate.checkout') }}"
                                   class="inline-flex items-center justify-center rounded-md border border-navy/20 px-6 py-3 text-sm font-semibold text-navy transition-colors hover:bg-navy hover:text-white">
                                    Cancel
                                </a>
                            </div>

                            <p class="mt-5 flex items-center gap-2 text-xs text-gray-500">
                                <svg class="h-4 w-4 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3l7 4v5c0 4.4-3 8.4-7 9-4-.6-7-4.6-7-9V7l7-4z" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Payment is handled entirely by PayPal. Your card details never reach our servers.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
    @include('partials.paypal-sdk')
@endpush
