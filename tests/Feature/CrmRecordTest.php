<?php

namespace Tests\Feature;

use App\Services\CrmDonations;
use App\Services\CrmException;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
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

    private function payload(): array
    {
        return (new CrmDonations)->buildRecordPayload(
            ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com', 'gift_aid' => true],
            [
                ['cause' => 'Zakat', 'amount' => 50, 'qty' => 1, 'frequency' => 'one-off'],
                ['cause' => 'Sadaqah', 'amount' => 10, 'qty' => 2, 'frequency' => 'monthly'],
            ],
            'NF-ABC123',
            'United Kingdom',
            'PayPal',
            'CAPTURE-9',
        );
    }

    public function test_payload_has_donor_and_items_but_no_return_url(): void
    {
        $p = $this->payload();

        $this->assertArrayNotHasKey('returnUrl', $p);
        $this->assertSame('NF-ABC123', $p['cartReference']);
        $this->assertSame('jane@example.com', $p['donor']['email']);
        $this->assertTrue($p['donor']['isGiftAid']);
        $this->assertCount(2, $p['items']);

        [$once, $monthly] = $p['items'];
        $this->assertSame('NF-ABC123-1', $once['externalReference']);
        $this->assertSame(50.0, $once['amount']);
        $this->assertSame('OneTime', $once['frequency']);
        $this->assertSame('Zakat', $once['donationType']);
        $this->assertSame('CAPTURE-9', $once['paymentReference']);
        $this->assertSame('PayPal', $once['paymentProvider']);
        $this->assertSame(20.0, $monthly['amount']); // 10 x 2
        $this->assertSame('Monthly', $monthly['frequency']);
        $this->assertSame(1, $monthly['interval']);
    }

    public function test_the_same_donation_always_gets_the_same_references(): void
    {
        $this->assertSame($this->payload()['items'][0]['externalReference'], $this->payload()['items'][0]['externalReference']);
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
                && json_decode($r->body(), true)['cartReference'] === 'NF-ABC123';
        });
    }

    public function test_an_already_recorded_donation_is_not_an_error(): void
    {
        Http::fake([self::URL => Http::response(['message' => 'duplicate'], 409)]);

        $this->assertIsArray((new CrmDonations)->recordPaid('GBP', $this->payload()));
    }

    public function test_a_rejection_throws_with_the_crm_reason(): void
    {
        Http::fake([self::URL => Http::response(['message' => 'The Donor field is required.'], 400)]);

        $this->expectException(CrmException::class);
        $this->expectExceptionMessage('The Donor field is required.');

        (new CrmDonations)->recordPaid('GBP', $this->payload());
    }

    public function test_only_currencies_with_record_keys_are_reported(): void
    {
        $crm = new CrmDonations;

        $this->assertTrue($crm->recordingEnabledFor('GBP'));
        $this->assertFalse($crm->recordingEnabledFor('CAD'));

        config(['crm.record.enabled' => false]);
        $this->assertFalse($crm->recordingEnabledFor('GBP'));
    }
}
