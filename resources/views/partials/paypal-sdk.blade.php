{{-- PayPal JS SDK. The client ID is public by design; the secret stays server-side. --}}
@php
    $nfPaypal = app(\App\Services\PayPal::class);
    $nfCurrency = region('currency', 'GBP');
@endphp

@if ($nfPaypal->isConfigured())
    <script src="https://www.paypal.com/sdk/js?client-id={{ $nfPaypal->clientId() }}&currency={{ $nfCurrency }}&intent=capture&components=buttons"
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
