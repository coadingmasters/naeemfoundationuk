<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Services\CrmDonations;
use App\Services\CrmException;
use Illuminate\Console\Command;

/**
 * Re-send paid PayPal donations the CRM has not recorded yet (it was down, or
 * the keys were not set when the donor paid). Safe to run repeatedly: the CRM
 * gets the same reference each time, and recorded donations are skipped.
 */
class RecordDonationsInCrm extends Command
{
    protected $signature = 'crm:record-donations
        {--since= : Only donations paid on or after this date (YYYY-MM-DD) — required}
        {--dry-run : List what would be sent, send nothing}';

    protected $description = 'Report paid PayPal donations to the CRM donation API (those not yet recorded)';

    public function handle(CrmDonations $crm): int
    {
        $since = (string) $this->option('since');

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $since)) {
            $this->error('Give a start date, e.g. --since=2026-11-23 (so old donations are never re-sent by accident).');

            return self::INVALID;
        }

        $countries = collect(config('countries.list'))->map(fn ($c) => $c['name'] ?? null);
        $sent = $skipped = $failed = 0;

        Donation::withoutGlobalScope('region')
            ->where('payment_provider', 'paypal')
            ->whereIn('status', ['paid', 'active'])
            ->where('paid_at', '>=', $since.' 00:00:00')
            ->orderBy('id')
            ->each(function (Donation $d) use ($crm, $countries, &$sent, &$skipped, &$failed) {
                $currency = (string) $d->currency;

                if (! empty($d->crm_details['recorded_at']) || ! $crm->recordingEnabledFor($currency)) {
                    $skipped++;

                    return;
                }

                $this->line("{$d->reference}  {$currency} {$d->total}  {$d->email}");

                if ($this->option('dry-run')) {
                    return;
                }

                try {
                    $payload = $crm->buildRecordPayload(
                        [
                            'first_name' => $d->first_name, 'last_name' => $d->last_name, 'email' => $d->email,
                            'phone' => $d->phone, 'billing_address' => $d->billing_address, 'city' => $d->city,
                            'postcode' => $d->postcode, 'gift_aid' => $d->gift_aid,
                        ],
                        (array) $d->items,
                        $d->reference,
                        (string) ($countries[$d->region] ?? 'United Kingdom'),
                        'PayPal',
                        (string) ($d->payment_id ?: $d->subscription_id),
                    );
                    $crm->recordPaid($currency, $payload);
                    CrmDonations::saveRecordState($d->reference, ['recorded_at' => now()->toDateTimeString(), 'record_error' => null]);
                    $sent++;
                } catch (CrmException $e) {
                    CrmDonations::saveRecordState($d->reference, ['record_error' => mb_substr($e->getMessage(), 0, 300), 'record_failed_at' => now()->toDateTimeString()]);
                    $this->warn('  failed: '.$e->getMessage());
                    $failed++;
                }
            });

        $this->info("Sent {$sent}, already recorded/not configured {$skipped}, failed {$failed}.");

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
