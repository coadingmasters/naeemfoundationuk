<?php

namespace Tests\Feature;

use App\Services\CrmDonations;
use App\Services\CrmException;
use Carbon\Carbon;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** The CRM "Donation API": a PayPal-paid donation is reported to the CRM. Database-free. */
class CrmRecordTest extends TestCase
{
    private const URL = 'https://api.lumrick.com/api/webhooks/donations';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'crm.record.enabled' => true,
            'crm.record.url' => self::URL,
            'crm.record.branches.GBP' => ['api_key' => 'drk_test_key', 'signing_secret' => 'drsec_test_secret'],
            'crm.record.branches.CAD' => ['api_key' => null, 'signing_secret' => null],
        ]);
    }

    private function payload(?array $items = null, array $donor = []): array
    {
        return (new CrmDonations)->buildPayload(
            $donor + ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com', 'gift_aid' => true],
            $items ?? [
                ['cause' => 'Zakat', 'amount' => 50, 'qty' => 1, 'frequency' => 'one-off'],
                ['cause' => 'Sadaqah', 'amount' => 10, 'qty' => 2, 'frequency' => 'monthly'],
            ],
            'NF-ABC123',
            'United Kingdom',
            'GBP',
            'I-SUB999',
            Carbon::parse('2026-11-24 10:30:00', 'Europe/London'),
        );
    }

    public function test_payload_follows_the_donation_api_shape(): void
    {
        $p = $this->payload();

        $this->assertArrayNotHasKey('returnUrl', $p);
        $this->assertArrayNotHasKey('cartReference', $p);
        $this->assertSame('2026-11-24T10:30:00Z', $p['paidAt']);
        $this->assertSame('GBP', $p['currency']);
        $this->assertSame('jane@example.com', $p['donor']['email']);
        $this->assertTrue($p['donor']['isGiftAid']);
        $this->assertCount(2, $p['items']);

        [$once, $monthly] = $p['items'];
        $this->assertSame('NF-ABC123-1', $once['externalReference']);
        $this->assertSame(50.0, $once['amount']);
        $this->assertSame('OneTime', $once['frequency']);
        $this->assertSame('Zakat', $once['donationType']);
        $this->assertSame('PayPal', $once['paymentMethod']);
        $this->assertArrayNotHasKey('subscriptionReference', $once);
        $this->assertSame('I-SUB999', $once['meta']['paymentId']);

        $this->assertSame(20.0, $monthly['amount']); // 10 x 2
        $this->assertSame('Monthly', $monthly['frequency']);
        $this->assertSame(1, $monthly['interval']);
        $this->assertSame('PayPal', $monthly['paymentMethod']);
        $this->assertSame('I-SUB999', $monthly['subscriptionReference']);
    }

    public function test_paid_at_is_sent_in_utc(): void
    {
        $p = (new CrmDonations)->buildPayload(
            ['first_name' => 'A', 'last_name' => 'B', 'email' => 'a@b.co'],
            [['cause' => 'Zakat', 'amount' => 5, 'qty' => 1, 'frequency' => 'one-off']],
            'NF-1', 'United Kingdom', 'gbp', 'CAP-1',
            Carbon::parse('2026-07-01 12:00:00', 'Europe/London'), // BST = UTC+1
        );

        $this->assertSame('2026-07-01T11:00:00Z', $p['paidAt']);
        $this->assertSame('GBP', $p['currency']);
    }

    public function test_several_regular_gifts_in_one_subscription_each_get_their_own_subscription_reference(): void
    {
        $p = $this->payload([
            ['cause' => 'Zakat', 'amount' => 10, 'qty' => 1, 'frequency' => 'monthly'],
            ['cause' => 'Sadaqah', 'amount' => 5, 'qty' => 1, 'frequency' => 'monthly'],
        ]);

        $this->assertSame('I-SUB999-1', $p['items'][0]['subscriptionReference']);
        $this->assertSame('I-SUB999-2', $p['items'][1]['subscriptionReference']);
    }

    public function test_the_same_donation_always_gets_the_same_references(): void
    {
        $this->assertSame($this->payload()['items'][0]['externalReference'], $this->payload()['items'][0]['externalReference']);
    }

    public function test_optional_donor_fields_are_cleaned_or_left_out_so_the_crm_never_refuses_the_gift(): void
    {
        $donor = $this->payload(null, ['phone' => 'tel: +44 (7960) 185-682 ext#1', 'postcode' => 'AL7 1TW!', 'billing_address' => '2 Falcon Gate', 'city' => 'Welwyn'])['donor'];

        $this->assertSame('+44 (7960) 185-682 1', $donor['phoneNumber']);
        $this->assertSame('AL7 1TW', $donor['postcode']);
        $this->assertSame('2 Falcon Gate, Welwyn', $donor['address']);

        $bare = $this->payload(null, ['phone' => '', 'postcode' => '', 'billing_address' => ''])['donor'];
        $this->assertArrayNotHasKey('phoneNumber', $bare);
        $this->assertArrayNotHasKey('postcode', $bare);
        $this->assertArrayNotHasKey('address', $bare);
    }

    public function test_lines_with_the_same_campaign_and_schedule_are_merged(): void
    {
        $p = $this->payload([
            ['cause' => 'Zakat', 'amount' => 10, 'qty' => 1, 'frequency' => 'one-off'],
            ['cause' => 'Zakat', 'amount' => 5, 'qty' => 2, 'frequency' => 'one-off'],
        ]);

        $this->assertCount(1, $p['items']);
        $this->assertSame(20.0, $p['items'][0]['amount']);
    }

    public function test_more_than_ten_distinct_lines_is_refused(): void
    {
        $items = [];
        foreach (['zakat', 'sadaqah', 'fitrana', 'kaffarah', 'fidya', 'aqeeqah', 'qurbani', 'gaza', 'flood', 'cambodia', 'prosthetic'] as $cause) {
            $items[] = ['cause' => $cause, 'amount' => 1, 'qty' => 1, 'frequency' => 'one-off'];
        }

        $this->expectException(CrmException::class);
        $this->payload($items);
    }

    #[DataProvider('causes')]
    public function test_cause_maps_to_the_right_campaign(string $cause, string $campaignId, string $type): void
    {
        $this->assertSame(['id' => $campaignId, 'type' => $type], (new CrmDonations)->campaignFor($cause));
    }

    public static function causes(): array
    {
        return [
            'zakat' => ['Zakat', 'd721e793-a3f4-441d-b8bd-a28f283a3ea9', 'Zakat'],
            'sadaqah' => ['Sadaqah Jariyah', '2f1928d6-ec20-4762-b611-bac7124ff8f8', 'Sadaqah'],
            'fitrana' => ['Zakat ul Fitr', '97910e28-d29c-4527-ba85-fa3b194949b0', 'Fitrana'],
            'qurbani' => ['Qurbani 2026', '35cd190b-6c09-4a65-8a89-1a605eba8721', 'Qurbani'],
            'water' => ['Build a Water Well', 'db7b1301-2803-4830-a424-22581d92e9cc', 'WaterPump'],
            'education' => ['Education for Orphans', 'f8972812-3658-4313-a844-6a5c99dd8046', 'Education'],
            'unknown' => ['Something new', '44eaa5a7-b1d9-4aa0-9d54-ed8a11862c79', 'General'],
        ];
    }

    public function test_it_posts_a_signed_request_with_the_donation_api_key(): void
    {
        Http::fake([self::URL => Http::response(['status' => 'recorded'], 201)]);

        (new CrmDonations)->recordPaid('GBP', $this->payload());

        Http::assertSent(function (HttpRequest $r) {
            $ts = $r->header('X-Timestamp')[0];
            $sig = 'sha256='.hash_hmac('sha256', $ts.'.'.$r->body(), 'drsec_test_secret');

            return $r->url() === self::URL
                && $r->header('X-Api-Key')[0] === 'drk_test_key'
                && $r->header('X-Signature')[0] === $sig
                && json_decode($r->body(), true)['items'][0]['externalReference'] === 'NF-ABC123-1';
        });
    }

    public function test_a_retry_of_an_already_recorded_donation_is_fine(): void
    {
        Http::fake([self::URL => Http::response(['duplicate' => true], 200)]);

        $this->assertTrue((new CrmDonations)->recordPaid('GBP', $this->payload())['duplicate']);
    }

    public function test_a_rejection_throws_with_the_crm_reason(): void
    {
        Http::fake([self::URL => Http::response(['message' => 'items[0] (NF-ABC123-1): paymentMethod is required'], 400)]);

        $this->expectException(CrmException::class);
        $this->expectExceptionMessage('paymentMethod is required');

        (new CrmDonations)->recordPaid('GBP', $this->payload());
    }

    public function test_a_409_reference_conflict_is_reported_as_an_error(): void
    {
        Http::fake([self::URL => Http::response(['message' => 'reference used for something different'], 409)]);

        $this->expectException(CrmException::class);

        (new CrmDonations)->recordPaid('GBP', $this->payload());
    }

    public function test_only_currencies_with_record_keys_are_reported(): void
    {
        $crm = new CrmDonations;

        $this->assertTrue($crm->enabledFor('GBP'));
        $this->assertFalse($crm->enabledFor('CAD'));

        config(['crm.record.enabled' => false]);
        $this->assertFalse($crm->enabledFor('GBP'));
    }
}
