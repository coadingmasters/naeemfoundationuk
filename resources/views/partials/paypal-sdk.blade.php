{{-- PayPal JS SDK. The client ID is public by design; the secret stays server-side.
     Pass $paypalSubscription = true to load the SDK in monthly-subscription mode. --}}
@php
    $nfPaypal = app(\App\Services\PayPal::class);
    $nfCurrency = region('currency', 'GBP');
    $nfSubscription = $paypalSubscription ?? false;
    // Subscriptions need intent=subscription + vaulting; one-off uses capture.
    $nfSdkParams = $nfSubscription
        ? 'vault=true&intent=subscription'
        : 'intent=capture&disable-funding=paylater,credit';
@endphp

@if ($nfPaypal->isConfigured())
    <script src="https://www.paypal.com/sdk/js?client-id={{ $nfPaypal->clientId() }}&currency={{ $nfCurrency }}&components=buttons&{{ $nfSdkParams }}"
            data-nf-paypal-sdk></script>
@else
    {{-- No credentials configured — tell the visitor rather than showing dead buttons. --}}
    <script>
        document.querySelectorAll('[data-paypal-buttons]').forEach((el) => {
            el.innerHTML = '<div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">'
                + 'Online payment is not available right now. Please contact us to complete your payment.</div>';
        });
    </script>
@endif
