{{--
    Branded wrapper around PayPal's Smart Buttons.

    PayPal renders its buttons inside a cross-origin iframe, so their look is
    fixed — everything around them is ours: the framed panel, the shimmer that
    holds the space while the SDK loads, and the trust row underneath.

    @param string $orderUrl     Route that creates the PayPal order
    @param string $captureUrl   Route that captures it
    @param string|null $form    Optional selector of a details form to send
--}}
@props(['orderUrl', 'captureUrl', 'form' => null])

<div class="nf-pay"
     data-paypal
     @if ($form) data-form="{{ $form }}" @endif
     data-order-url="{{ $orderUrl }}"
     data-capture-url="{{ $captureUrl }}">

    <div class="nf-pay__head">
        <span class="nf-pay__lock" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="4.5" y="10.5" width="15" height="10" rx="2.5"/>
                <path d="M8.5 10.5V7.2a3.5 3.5 0 0 1 7 0v3.3" stroke-linecap="round"/>
            </svg>
        </span>
        <div>
            <p class="nf-pay__title">Choose how to pay</p>
            <p class="nf-pay__sub">Secure checkout &mdash; takes a few seconds</p>
        </div>
    </div>

    <div data-paypal-error class="nf-pay__error" hidden></div>

    <div class="nf-pay__frame">
        {{-- Holds the space (and the eye) while the SDK boots. --}}
        <div class="nf-pay__skeleton" data-paypal-skeleton aria-hidden="true">
            <span class="nf-pay__bar"></span>
            <span class="nf-pay__bar"></span>
        </div>

        {{-- PayPal renders here. --}}
        <div data-paypal-buttons class="nf-pay__buttons"></div>
    </div>

    {{-- What the donor can actually pay with --}}
    <div class="nf-pay__trust">
        <span class="nf-pay__cards" aria-label="Visa, Mastercard, American Express and PayPal accepted">
            <svg viewBox="0 0 40 26" role="img"><title>Visa</title><rect width="40" height="26" rx="4" fill="#1A1F71"/><path d="M16.7 18.2h-2.4l1.5-9.4h2.4l-1.5 9.4zm8.7-9.2c-.5-.2-1.2-.4-2.1-.4-2.3 0-4 1.2-4 3 0 1.3 1.2 2 2.1 2.5.9.4 1.2.7 1.2 1.1 0 .6-.7.9-1.4.9-1 0-1.5-.1-2.3-.5l-.3-.1-.3 2c.6.3 1.6.5 2.7.5 2.5 0 4.1-1.2 4.1-3.1 0-1-.6-1.8-2-2.5-.8-.4-1.3-.7-1.3-1.1 0-.4.4-.8 1.4-.8.8 0 1.4.2 1.8.4l.2.1.3-1.9zm6.3-.2h-1.9c-.6 0-1 .2-1.3.8l-3.6 8.6h2.5l.5-1.4h3.1l.3 1.4h2.2l-1.9-9.4zm-3 6.1l1-2.6.3-.9.2.8.6 2.7h-2.1zM12.9 8.8l-2.4 6.4-.3-1.3c-.4-1.5-1.8-3.2-3.4-4l2.2 8.3h2.5l3.8-9.4h-2.4z" fill="#fff"/><path d="M8.2 8.8H4.4l-.1.2c3 .8 5 2.6 5.8 4.9l-.8-4.3c-.1-.6-.5-.8-1.1-.8z" fill="#F9A51A"/></svg>
            <svg viewBox="0 0 40 26" role="img"><title>Mastercard</title><rect width="40" height="26" rx="4" fill="#F4F4F4"/><circle cx="16" cy="13" r="7.2" fill="#EB001B"/><circle cx="24" cy="13" r="7.2" fill="#F79E1B"/><path d="M20 7.6a7.2 7.2 0 0 0 0 10.8 7.2 7.2 0 0 0 0-10.8z" fill="#FF5F00"/></svg>
            <svg viewBox="0 0 40 26" role="img"><title>American Express</title><rect width="40" height="26" rx="4" fill="#2E77BC"/><path d="M6 10.5h4l.7 1.6.7-1.6h10.9v.8l.6-.8h3.4l.6.8v-.8h4.2l1 1.2 1-1.2H36v5h-2.9l-.9-1.1-.9 1.1H19.8v-.9h-.5v.9H6.9l-.4-1H5.4l-.4 1H2.5l3.5-5zm1.1 1.1l-1.2 2.8h.9l.2-.6h1.5l.2.6h1.7v-2.2l1 2.2h.8l1-2.2v2.2h1v-2.8h-1.6l-.8 1.9-.8-1.9H7.1zm7.3 0v2.8h4.3v-.7h-3.3v-.5h3.2v-.6h-3.2v-.4h3.3v-.6h-4.3zm5 0v2.8h1v-.9h1.1c.9 0 1.3-.4 1.3-1s-.4-.9-1.2-.9h-2.2zm1 .6h1.1c.3 0 .4.1.4.3s-.1.4-.4.4h-1.1v-.7z" fill="#fff"/></svg>
            <svg viewBox="0 0 40 26" role="img"><title>PayPal</title><rect width="40" height="26" rx="4" fill="#F4F4F4"/><path d="M14.6 7.4h4.6c2.5 0 3.4 1.3 3.2 3.1-.3 3-2.1 4.7-4.6 4.7h-1.3c-.3 0-.5.2-.6.6l-.5 3.3c0 .2-.1.3-.3.3h-2.4c-.2 0-.3-.2-.3-.5l1.6-10.6c.1-.5.3-.9.6-.9z" fill="#003087"/><path d="M23.4 10.3c-.3 2.9-2.1 4.6-4.7 4.6h-1.3c-.3 0-.5.2-.6.6l-.6 3.9h-1.4l.4-2.6c.1-.4.3-.6.6-.6h1.3c2.5 0 4.3-1.7 4.6-4.7.1-.5 0-1-.2-1.3.6.2 1 .6.9 2.1z" fill="#0070E0"/></svg>
        </span>
        <span class="nf-pay__powered">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                <path d="M12 3l7 3.2v5c0 4.3-3 8.2-7 8.8-4-.6-7-4.5-7-8.8v-5L12 3z" stroke-linejoin="round"/>
                <path d="M9.2 12.2l2 2 3.6-3.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            256-bit encrypted
        </span>
    </div>

    {{-- Only visible while the capture call is in flight --}}
    <div data-paypal-busy class="nf-pay__busy" hidden>
        <span class="nf-pay__spinner" aria-hidden="true"></span>
        <span>Completing your payment&hellip; please don&rsquo;t close this page.</span>
    </div>
</div>
