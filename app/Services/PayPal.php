<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Thin wrapper over the PayPal Orders v2 REST API.
 *
 * Only the server ever sees the client secret. The browser gets the client ID
 * (public by design) so the JS SDK can render the buttons, but every amount is
 * calculated here from the live basket — a tampered client payload can never
 * change what is charged.
 */
class PayPal
{
    /** Config for the active mode (sandbox or live). */
    private array $cfg;

    public function __construct()
    {
        $mode = config('paypal.mode', 'sandbox');
        $this->cfg = config("paypal.{$mode}", []);
    }

    /** Are credentials present for the active mode? */
    public function isConfigured(): bool
    {
        return ! empty($this->cfg['client_id']) && ! empty($this->cfg['client_secret']);
    }

    /** Public client ID — safe to render into the page for the JS SDK. */
    public function clientId(): ?string
    {
        return $this->cfg['client_id'] ?? null;
    }

    public function mode(): string
    {
        return config('paypal.mode', 'sandbox');
    }

    /**
     * OAuth2 access token, cached until just before it expires.
     *
     * The cache key includes the client ID so switching sandbox/live keys can
     * never reuse a token minted for the other environment.
     */
    public function accessToken(): string
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('PayPal credentials are not configured.');
        }

        $key = 'paypal:token:'.md5($this->cfg['client_id']);

        return Cache::remember($key, now()->addMinutes(50), function () {
            $res = Http::asForm()
                ->withBasicAuth($this->cfg['client_id'], $this->cfg['client_secret'])
                ->timeout(20)
                ->post($this->cfg['base_url'].'/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if (! $res->successful()) {
                $this->logFailure('oauth token', $res);
                throw new RuntimeException('Could not authenticate with PayPal.');
            }

            return (string) $res->json('access_token');
        });
    }

    /**
     * Create an order and return PayPal's order ID.
     *
     * @param  float   $amount     Charged total, already calculated server-side.
     * @param  string  $currency   ISO code — GBP / USD / CAD.
     * @param  string  $reference  Our own reference, echoed back on capture.
     */
    public function createOrder(float $amount, string $currency, string $reference, string $description = ''): string
    {
        $res = $this->request()->post($this->cfg['base_url'].'/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                // custom_id (not invoice_id) so a retried payment is never
                // rejected by PayPal as a duplicate invoice.
                'custom_id' => $reference,
                'description' => mb_substr($description ?: config('app.name'), 0, 127),
                'amount' => [
                    'currency_code' => $currency,
                    'value' => number_format($amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'brand_name' => mb_substr((string) config('paypal.brand_name'), 0, 127),
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
            ],
        ]);

        if (! $res->successful()) {
            $this->logFailure('create order', $res);
            throw new RuntimeException('Could not start the PayPal payment.');
        }

        return (string) $res->json('id');
    }

    /**
     * Capture an approved order — this is the step that actually moves money.
     *
     * @return array{status: string, capture_id: ?string, amount: ?string, currency: ?string, payer_email: ?string}
     */
    public function captureOrder(string $orderId): array
    {
        $res = $this->request()->post($this->cfg['base_url']."/v2/checkout/orders/{$orderId}/capture");

        if (! $res->successful()) {
            $this->logFailure('capture order', $res);
            throw new RuntimeException('PayPal could not complete the payment.');
        }

        $capture = $res->json('purchase_units.0.payments.captures.0', []);

        return [
            'status' => (string) $res->json('status'),
            'capture_id' => $capture['id'] ?? null,
            'amount' => $capture['amount']['value'] ?? null,
            'currency' => $capture['amount']['currency_code'] ?? null,
            'payer_email' => $res->json('payer.email_address'),
        ];
    }

    /** Read an order back from PayPal (used to double-check amounts). */
    public function getOrder(string $orderId): array
    {
        $res = $this->request()->get($this->cfg['base_url']."/v2/checkout/orders/{$orderId}");

        if (! $res->successful()) {
            $this->logFailure('get order', $res);
            throw new RuntimeException('Could not read the PayPal order.');
        }

        return $res->json();
    }

    /**
     * Verify a webhook really came from PayPal.
     *
     * Returns false when no webhook ID is configured, so an unconfigured
     * install can never be tricked into trusting a forged notification.
     */
    public function verifyWebhook(array $headers, array $body): bool
    {
        $webhookId = $this->cfg['webhook_id'] ?? null;

        if (empty($webhookId)) {
            return false;
        }

        $header = fn (string $name) => $headers[strtolower($name)][0] ?? null;

        $res = $this->request()->post($this->cfg['base_url'].'/v1/notifications/verify-webhook-signature', [
            'auth_algo' => $header('paypal-auth-algo'),
            'cert_url' => $header('paypal-cert-url'),
            'transmission_id' => $header('paypal-transmission-id'),
            'transmission_sig' => $header('paypal-transmission-sig'),
            'transmission_time' => $header('paypal-transmission-time'),
            'webhook_id' => $webhookId,
            'webhook_event' => $body,
        ]);

        return $res->successful() && $res->json('verification_status') === 'SUCCESS';
    }

    private function request()
    {
        return Http::withToken($this->accessToken())
            ->acceptJson()
            ->timeout(30);
    }

    /** Log the failure without ever writing credentials into the log. */
    private function logFailure(string $step, Response $res): void
    {
        Log::error("PayPal {$step} failed", [
            'status' => $res->status(),
            'body' => mb_substr($res->body(), 0, 1000),
        ]);
    }
}
