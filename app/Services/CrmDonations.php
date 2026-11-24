<?php

namespace App\Services;

use App\Models\Donation;
use Carbon\CarbonInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Client for the CRM's "Donation API".
 *
 * The site takes the payment itself (PayPal). Once it has succeeded, the paid
 * donation is reported here so the CRM records it (recurring gifts show up under
 * subscriptions). Nothing is charged by the CRM and the donor is never redirected.
 *
 * Every request carries X-Api-Key, X-Timestamp and
 *   X-Signature: sha256=hex(HMAC_SHA256(secret, timestamp . "." . rawBody))
 * The CRM rejects timestamps older than 5 minutes.
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

    /** The CRM accepts 1–10 items per request. */
    public const MAX_ITEMS = 10;

    /** How the CRM names the way the donor paid (it requires one on every item). */
    public const PAYMENT_METHOD = 'PayPal';

    /** @return array{api_key: string, signing_secret: string}|null */
    public function credentials(string $currency): ?array
    {
        $branch = config('crm.record.branches.'.strtoupper($currency));

        if (empty($branch['api_key']) || empty($branch['signing_secret'])) {
            return null;
        }

        return ['api_key' => $branch['api_key'], 'signing_secret' => $branch['signing_secret']];
    }

    /** Should a paid donation in this currency be reported to the CRM? */
    public function enabledFor(string $currency): bool
    {
        return (bool) config('crm.record.enabled', true) && $this->credentials($currency) !== null;
    }

    public static function sign(string $secret, int|string $timestamp, string $payload): string
    {
        return hash_hmac('sha256', $timestamp.'.'.$payload, $secret);
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
     * The body for a donation that has ALREADY been paid on this site.
     *
     * References are derived from the donation reference, so sending the same
     * donation twice is harmless (the CRM answers 200 "duplicate").
     * Lines with the same campaign, type and frequency are merged (quantity is
     * folded into the amount) to stay within the CRM's 10-item limit.
     *
     * @param  array<string, mixed>  $donor  Checkout details (first_name, last_name, email, phone, billing_address, city, postcode, gift_aid).
     * @param  array<int, array<string, mixed>>  $items  DonationCart items.
     * @param  string  $paymentId  PayPal capture id (one-off) or subscription id (regular gift).
     * @return array<string, mixed>
     *
     * @throws CrmException
     */
    public function buildPayload(array $donor, array $items, string $reference, string $country, string $currency, string $paymentId, ?CarbonInterface $paidAt = null): array
    {
        $lines = array_values($this->mergeLines($items));
        $recurring = count(array_filter($lines, fn ($l) => $l['frequency'] !== 'OneTime'));

        $rows = [];
        $recurringNo = 0;
        foreach ($lines as $n => $line) {
            $row = [
                'externalReference' => $reference.'-'.($n + 1),
                'campaignId' => $line['campaignId'],
                'amount' => round($line['amount'], 2),
                'donationType' => $line['donationType'],
                'paymentMethod' => self::PAYMENT_METHOD,
                'frequency' => $line['frequency'],
                'meta' => ['source' => 'website', 'donationReference' => $reference, 'paymentId' => $paymentId],
            ];

            if ($line['frequency'] !== 'OneTime') {
                $recurringNo++;
                $row['interval'] = 1; // every 1 day/week/month/year
                // The PayPal subscription id is the stable id; if one subscription
                // covers several causes each cause gets its own suffix.
                $row['subscriptionReference'] = $recurring > 1 ? $paymentId.'-'.$recurringNo : $paymentId;
            }

            $rows[] = $row;
        }

        return [
            'paidAt' => ($paidAt ?? now())->copy()->utc()->format('Y-m-d\TH:i:s\Z'),
            'currency' => strtoupper($currency),
            'donor' => $this->donorBlock($donor, $country),
            'items' => $rows,
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

    /**
     * Only email, first name and last name are required. The optional fields are
     * cleaned to the characters the CRM accepts (a stray character would make it
     * refuse the WHOLE donation) and left out when empty.
     *
     * @return array<string, mixed>
     */
    private function donorBlock(array $donor, string $country): array
    {
        $address = trim(($donor['billing_address'] ?? '').(! empty($donor['city']) ? ', '.$donor['city'] : ''));
        $phone = trim((string) preg_replace('/[^0-9 +\-.()\[\]]/', '', (string) ($donor['phone'] ?? '')));
        $postcode = trim((string) preg_replace('/[^A-Za-z0-9 \-]/', '', (string) ($donor['postcode'] ?? '')));

        return array_filter([
            'email' => trim((string) ($donor['email'] ?? '')),
            'firstName' => trim((string) ($donor['first_name'] ?? '')),
            'lastName' => trim((string) ($donor['last_name'] ?? '')),
            'phoneNumber' => $phone,
            'address' => $address,
            'postcode' => $postcode,
            'country' => $country,
            'isGiftAid' => (bool) ($donor['gift_aid'] ?? false),
        ], fn ($v) => $v !== '');
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
     * 201 = recorded, 200 = this donation was already recorded (a retry is safe).
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws CrmException
     */
    public function recordPaid(string $currency, array $payload): array
    {
        $credentials = $this->credentials($currency)
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

        if (! in_array($response->status(), [200, 201], true)) {
            // The body never contains our secrets — safe to log for diagnosis.
            Log::error('CRM record rejected', [
                'donation' => $payload['items'][0]['meta']['donationReference'] ?? null,
                'status' => $response->status(),
                'body' => mb_substr($response->body(), 0, 1000),
            ]);

            $reason = trim((string) $response->json('message', ''));

            throw new CrmException('The CRM did not accept the donation (HTTP '.$response->status().')'.($reason !== '' ? ': '.mb_substr($reason, 0, 300) : '.'));
        }

        return (array) $response->json();
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
}
