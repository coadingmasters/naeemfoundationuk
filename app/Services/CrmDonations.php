<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Server-to-server client for the CRM's donation-cart webhook.
 *
 * Every request carries X-Api-Key, X-Timestamp and
 *   X-Signature: sha256=hex(HMAC_SHA256(secret, timestamp . "." . body))
 * where body is the exact JSON string sent (or the cartReference for the
 * status GET). The CRM rejects timestamps older than 5 minutes.
 */
class CrmDonations
{
    /** Site basket frequency -> CRM frequency. */
    public const FREQUENCIES = [
        'one-off' => 'OneTime',
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];

    /** The CRM accepts 1–10 items per cart. */
    public const MAX_ITEMS = 10;

    /**
     * Is the CRM flow active for this visitor AND configured for this currency?
     * Active = switched on for everyone, or this browser opened the private
     * preview link (so it can be tested without touching real donors).
     */
    public function enabledFor(string $currency): bool
    {
        return ((bool) config('crm.enabled') || $this->previewActive()) && $this->credentials($currency) !== null;
    }

    /**
     * Should THIS basket go through the CRM? Same as enabledFor(), plus: a basket
     * with a recurring gift stays on the site's PayPal flow until CRM_RECURRING is on.
     *
     * @param  array<int, array<string, mixed>>  $items
     */
    public function enabledForBasket(string $currency, array $items): bool
    {
        if (! $this->enabledFor($currency)) {
            return false;
        }

        if (config('crm.recurring')) {
            return true;
        }

        foreach ($items as $item) {
            if (($item['frequency'] ?? 'one-off') !== 'one-off') {
                return false;
            }
        }

        return true;
    }

    /** Did this browser session opt in via the private preview link? */
    public function previewActive(): bool
    {
        return request()->hasSession() && (bool) request()->session()->get('crm_preview');
    }

    /** @return array{api_key: string, signing_secret: string}|null */
    public function credentials(string $currency): ?array
    {
        $branch = config('crm.branches.'.strtoupper($currency));

        if (empty($branch['api_key']) || empty($branch['signing_secret'])) {
            return null;
        }

        return ['api_key' => $branch['api_key'], 'signing_secret' => $branch['signing_secret']];
    }

    public static function sign(string $secret, int|string $timestamp, string $payload): string
    {
        return hash_hmac('sha256', $timestamp.'.'.$payload, $secret);
    }

    /**
     * A CRM cart reference is unique per cart, and re-sending one returns the
     * SAME cart. So it must change whenever the basket changes (donor edits
     * amounts) but stay stable for a double-click on the same basket.
     *
     * @param  array<int, array<string, mixed>>  $items
     */
    public static function cartReference(string $donationReference, array $items): string
    {
        $fingerprint = array_map(fn ($i) => [
            $i['cause'] ?? '', (float) ($i['amount'] ?? 0), (int) ($i['qty'] ?? 1), $i['frequency'] ?? 'one-off',
        ], $items);

        return $donationReference.'-'.substr(sha1(json_encode($fingerprint)), 0, 8);
    }

    /** @return array{id: string, type: string} */
    public function campaignFor(string $cause): array
    {
        $needle = mb_strtolower($cause);

        foreach (config('crm.campaigns', []) as $rule) {
            foreach ($rule['match'] as $word) {
                if (str_contains($needle, $word)) {
                    return ['id' => $rule['id'], 'type' => $rule['type'] ?: 'General'];
                }
            }
        }

        return ['id' => config('crm.default_campaign'), 'type' => 'General'];
    }

    /**
     * Turn the site basket + donor into the CRM cart body.
     *
     * Lines with the same campaign, type and frequency are merged (quantity is
     * folded into the amount) to stay within the CRM's 10-item limit.
     *
     * @param  array<string, mixed>  $donor  Checkout details (first_name, last_name, email, phone, billing_address, city, postcode, gift_aid).
     * @param  array<int, array<string, mixed>>  $items  DonationCart items.
     * @return array<string, mixed>
     *
     * @throws CrmException
     */
    public function buildPayload(array $donor, array $items, string $donationReference, string $returnUrl, string $country): array
    {
        $cartReference = self::cartReference($donationReference, $items);

        $lines = [];
        foreach ($items as $item) {
            $frequency = $item['frequency'] ?? 'one-off';

            if (! isset(self::FREQUENCIES[$frequency])) {
                throw new CrmException('Unsupported donation frequency: '.$frequency);
            }

            $campaign = $this->campaignFor((string) ($item['cause'] ?? ''));
            $key = $campaign['id'].'|'.$campaign['type'].'|'.$frequency;

            $lines[$key]['campaignId'] = $campaign['id'];
            $lines[$key]['donationType'] = mb_substr($campaign['type'], 0, 120);
            $lines[$key]['frequency'] = self::FREQUENCIES[$frequency];
            $lines[$key]['amount'] = ($lines[$key]['amount'] ?? 0) + (float) $item['amount'] * (int) ($item['qty'] ?? 1);
        }

        if ($lines === []) {
            throw new CrmException('The basket is empty.');
        }

        if (count($lines) > self::MAX_ITEMS) {
            throw new CrmException('Too many different donations in one basket (maximum '.self::MAX_ITEMS.').');
        }

        $cartItems = [];
        foreach (array_values($lines) as $n => $line) {
            $row = [
                'externalReference' => $cartReference.'-'.($n + 1),
                'campaignId' => $line['campaignId'],
                'amount' => round($line['amount'], 2),
                'donationType' => $line['donationType'],
                'frequency' => $line['frequency'],
            ];

            if ($line['frequency'] !== 'OneTime') {
                $row['interval'] = 1; // every 1 day/week/month/year
            }

            $cartItems[] = $row;
        }

        $address = trim(($donor['billing_address'] ?? '').(! empty($donor['city']) ? ', '.$donor['city'] : ''));

        return [
            'cartReference' => $cartReference,
            'returnUrl' => $returnUrl,
            'donor' => [
                'email' => $donor['email'] ?? '',
                'firstName' => $donor['first_name'] ?? '',
                'lastName' => $donor['last_name'] ?? '',
                'phoneNumber' => $donor['phone'] ?? '',
                'address' => $address,
                'postcode' => $donor['postcode'] ?? '',
                'country' => $country,
                'isGiftAid' => (bool) ($donor['gift_aid'] ?? false),
            ],
            'items' => $cartItems,
        ];
    }

    /**
     * Create the cart on the CRM. Returns the 201 body
     * (cartId, cartReference, status, redirectUrl, redirectExpiresAt, items[]).
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws CrmException
     */
    public function createCart(string $currency, array $payload): array
    {
        $credentials = $this->requireCredentials($currency);
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        try {
            $response = Http::withHeaders($this->headers($credentials, $body))
                ->withBody($body, 'application/json')
                ->timeout((int) config('crm.timeout', 20))
                ->post(config('crm.webhook_url'));
        } catch (ConnectionException $e) {
            Log::error('CRM cart: connection failed', ['cart' => $payload['cartReference'] ?? null, 'error' => $e->getMessage()]);

            throw new CrmException('Could not reach the donation service.', 0, $e);
        }

        if ($response->status() !== 201 || ! is_array($response->json())) {
            // The body never contains our secrets — safe to log for diagnosis.
            Log::error('CRM cart rejected', [
                'cart' => $payload['cartReference'] ?? null,
                'status' => $response->status(),
                'body' => mb_substr($response->body(), 0, 1000),
            ]);

            throw new CrmException('The donation service did not accept the basket (HTTP '.$response->status().').');
        }

        $redirect = (string) $response->json('redirectUrl', '');

        if (! str_starts_with($redirect, 'https://')) {
            Log::error('CRM cart: missing/insecure redirectUrl', ['cart' => $payload['cartReference'] ?? null]);

            throw new CrmException('The donation service returned no checkout page.');
        }

        return $response->json();
    }

    /**
     * Ask the CRM for a cart's outcome (signed GET {webhookUrl}/{cartReference}).
     * The return-URL query string is only a hint — this is the source of truth.
     *
     * @return array<string, mixed>
     *
     * @throws CrmException
     */
    public function cartStatus(string $currency, string $cartReference): array
    {
        $credentials = $this->requireCredentials($currency);

        try {
            $response = Http::withHeaders($this->headers($credentials, $cartReference))
                ->timeout((int) config('crm.timeout', 20))
                ->get(rtrim(config('crm.webhook_url'), '/').'/'.rawurlencode($cartReference));
        } catch (ConnectionException $e) {
            throw new CrmException('Could not reach the donation service.', 0, $e);
        }

        if (! $response->successful() || ! is_array($response->json())) {
            Log::error('CRM cart status failed', ['cart' => $cartReference, 'status' => $response->status(), 'body' => mb_substr($response->body(), 0, 500)]);

            throw new CrmException('Could not confirm the payment (HTTP '.$response->status().').');
        }

        return $response->json();
    }

    /**
     * Reduce whatever status string the CRM reports to one of:
     * paid | pending | cancelled | failed | unknown.
     * Anything unrecognised is 'unknown' and is NEVER treated as paid.
     */
    public static function normaliseStatus(?string $status): string
    {
        $s = mb_strtolower((string) $status);

        return match (true) {
            $s === '' => 'unknown',
            str_contains($s, 'cancel') => 'cancelled',
            str_contains($s, 'fail'), str_contains($s, 'expire'), str_contains($s, 'declin'), str_contains($s, 'reject') => 'failed',
            str_contains($s, 'pend'), str_contains($s, 'process'), str_contains($s, 'await'), str_contains($s, 'partial') => 'pending',
            str_contains($s, 'paid'), str_contains($s, 'success'), str_contains($s, 'complete'), str_contains($s, 'succeed') => 'paid',
            default => 'unknown',
        };
    }

    /** @param  array{api_key: string, signing_secret: string}  $credentials */
    private function headers(array $credentials, string $signedPayload): array
    {
        $timestamp = time();

        return [
            'Accept' => 'application/json',
            'X-Api-Key' => $credentials['api_key'],
            'X-Timestamp' => (string) $timestamp,
            'X-Signature' => 'sha256='.self::sign($credentials['signing_secret'], $timestamp, $signedPayload),
        ];
    }

    /** @return array{api_key: string, signing_secret: string} */
    private function requireCredentials(string $currency): array
    {
        return $this->credentials($currency)
            ?? throw new CrmException('The donation service is not configured for '.strtoupper($currency).'.');
    }
}
