<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

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
     * A CRM cart reference is unique per cart, and re-sending one with different
     * contents is refused ("already used for a different cart"). So it must
     * change whenever ANYTHING we send changes (gifts, donor details) but stay
     * stable for an exact repeat, such as a double-click.
     *
     * @param  array<string, mixed>  $contents  Everything that makes the cart what it is.
     */
    public static function cartReference(string $donationReference, array $contents): string
    {
        return $donationReference.'-'.substr(sha1(json_encode($contents)), 0, 8);
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
        $lines = $this->mergeLines($items);
        $donorBlock = $this->donorBlock($donor, $country);

        $cartReference = self::cartReference($donationReference, [$returnUrl, $donorBlock, array_values($lines)]);

        return [
            'cartReference' => $cartReference,
            'returnUrl' => $returnUrl,
            'donor' => $donorBlock,
            'items' => $this->cartItems($lines, $cartReference),
        ];
    }

    /**
     * Group basket lines by campaign + type + frequency (quantity folded into the
     * amount) to stay within the CRM's item limit.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, array{campaignId: string, donationType: string, frequency: string, amount: float}>
     *
     * @throws CrmException
     */
    private function mergeLines(array $items): array
    {
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

        return $lines;
    }

    /** @return array<string, mixed> */
    private function donorBlock(array $donor, string $country): array
    {
        $address = trim(($donor['billing_address'] ?? '').(! empty($donor['city']) ? ', '.$donor['city'] : ''));

        return [
            'email' => $donor['email'] ?? '',
            'firstName' => $donor['first_name'] ?? '',
            'lastName' => $donor['last_name'] ?? '',
            'phoneNumber' => $donor['phone'] ?? '',
            'address' => $address,
            'postcode' => $donor['postcode'] ?? '',
            'country' => $country,
            'isGiftAid' => (bool) ($donor['gift_aid'] ?? false),
        ];
    }

    /**
     * @param  array<string, array<string, mixed>>  $lines
     * @param  array<string, mixed>  $extra  Added to every item (e.g. the payment reference).
     * @return array<int, array<string, mixed>>
     */
    private function cartItems(array $lines, string $cartReference, array $extra = []): array
    {
        $cartItems = [];
        foreach (array_values($lines) as $n => $line) {
            $row = [
                'externalReference' => $cartReference.'-'.($n + 1),
                'campaignId' => $line['campaignId'],
                'amount' => round($line['amount'], 2),
                'donationType' => $line['donationType'],
                'frequency' => $line['frequency'],
            ] + $extra;

            if ($line['frequency'] !== 'OneTime') {
                $row['interval'] = 1; // every 1 day/week/month/year
            }

            $cartItems[] = $row;
        }

        return $cartItems;
    }

    // ------------------------------------------------------------------
    // "Donation API": the site takes the payment itself (PayPal), then tells
    // the CRM so it can record the gift as PAID. Nothing is charged by the CRM
    // and the donor is not redirected. Separate key + secret from the cart API.
    // ------------------------------------------------------------------

    /** @return array{api_key: string, signing_secret: string}|null */
    public function recordCredentials(string $currency): ?array
    {
        $branch = config('crm.record.branches.'.strtoupper($currency));

        if (empty($branch['api_key']) || empty($branch['signing_secret'])) {
            return null;
        }

        return ['api_key' => $branch['api_key'], 'signing_secret' => $branch['signing_secret']];
    }

    /** Should a paid donation in this currency be reported to the CRM? */
    public function recordingEnabledFor(string $currency): bool
    {
        return (bool) config('crm.record.enabled', true) && $this->recordCredentials($currency) !== null;
    }

    /**
     * The body for a donation that has ALREADY been paid on this site.
     * The reference is stable, so sending the same donation twice is harmless.
     *
     * @param  array<string, mixed>  $donor  Checkout details.
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, mixed>
     *
     * @throws CrmException
     */
    public function buildRecordPayload(array $donor, array $items, string $reference, string $country, string $provider, string $paymentId): array
    {
        $lines = $this->mergeLines($items);

        return [
            'cartReference' => $reference,
            'donor' => $this->donorBlock($donor, $country),
            'items' => $this->cartItems($lines, $reference, [
                'paymentProvider' => $provider,
                'paymentReference' => $paymentId,
            ]),
        ];
    }

    /** Remember on our donation row whether the CRM has been told (never throws). */
    public static function saveRecordState(string $reference, array $state): void
    {
        try {
            if (! Schema::hasTable('donations')) {
                return;
            }

            $row = Donation::withoutGlobalScope('region')->where('reference', $reference)->first();

            if ($row) {
                $row->crm_details = array_merge((array) $row->crm_details, $state);
                $row->save();
            }
        } catch (Throwable $e) {
            Log::error('CRM record state not saved', ['reference' => $reference, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Tell the CRM a donation was paid. Returns the CRM's reply.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws CrmException
     */
    public function recordPaid(string $currency, array $payload): array
    {
        $credentials = $this->recordCredentials($currency)
            ?? throw new CrmException('The CRM donation API is not configured for '.strtoupper($currency).'.');
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        try {
            $response = Http::withHeaders($this->headers($credentials, $body))
                ->withBody($body, 'application/json')
                ->timeout((int) config('crm.record.timeout', 10))
                ->retry(2, 300, fn ($e) => $e instanceof ConnectionException, throw: false)
                ->post(config('crm.record.url'));
        } catch (ConnectionException $e) {
            throw new CrmException('Could not reach the CRM.', 0, $e);
        }

        // 200/201 = recorded (200 = this reference was already recorded); 409 = same.
        if (! in_array($response->status(), [200, 201, 409], true)) {
            Log::error('CRM record rejected', [
                'reference' => $payload['cartReference'] ?? null,
                'status' => $response->status(),
                'body' => mb_substr($response->body(), 0, 1000),
            ]);

            $reason = trim((string) $response->json('message', ''));

            throw new CrmException('The CRM did not accept the donation (HTTP '.$response->status().')'.($reason !== '' ? ': '.mb_substr($reason, 0, 300) : '.'));
        }

        return (array) $response->json();
    }

    /**
     * Create the cart on the CRM. Returns the 201 (or 200 for a repeat) body
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

        // 201 = new cart; 200 = the same cart sent again (double-click, refresh) — same body.
        if (! in_array($response->status(), [200, 201], true) || ! is_array($response->json())) {
            // The body never contains our secrets — safe to log for diagnosis.
            Log::error('CRM cart rejected', [
                'cart' => $payload['cartReference'] ?? null,
                'status' => $response->status(),
                'body' => mb_substr($response->body(), 0, 1000),
            ]);

            $reason = trim((string) $response->json('message', ''));

            throw new CrmException('The donation service did not accept the basket (HTTP '.$response->status().')'.($reason !== '' ? ': '.mb_substr($reason, 0, 300) : '.'));
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
