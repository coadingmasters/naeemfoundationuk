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
        foreach (['zakat', 'sadaqah', 'fitrana', 'kaffarah', 'fidya', 'aqeeqah', 'qurbani', 'hostel', 'widow', 'cambodia', 'prosthetic'] as $cause) {
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
            'Education Sponsorships' => ['Education Sponsorships', '71369324-566b-42b7-89ce-552d76537367', 'Education'],
            'Food & Sustenance' => ['Food & Sustenance', 'f2e2fc87-4ac0-436a-951d-0ce8ec9785fb', 'General'],
            'Hostel for Students / Orphans' => ['Hostel for Students / Orphans', 'd9e4a723-fe95-4289-9854-73cbb4b35514', 'General'],
            'Prosthetic Limb' => ['Prosthetic Limb', 'a23b59cc-e7f9-4455-a50b-966dd708ba7f', 'General'],
            'Clean Water' => ['Clean Water', 'e82047eb-c1e5-4dc9-9687-a693c3c72f1b', 'WaterPump'],
            'Orphans Sponsorships' => ['Orphans Sponsorships', '88c7887f-e1b3-453f-a8eb-08e8e96dd863', 'General'],
            'Cambodia Education & Welfare' => ['Cambodia Education & Welfare', '876082d6-1ea5-46e8-b351-7ec02537ff96', 'General'],
            'Food Appeal' => ['Food Appeal', '45dd7eec-d65a-4de3-8378-8aec026ed8be', 'General'],
            'Healthcare' => ['Healthcare', 'cab69d92-4908-4e4d-b76b-76d4c5020866', 'General'],
            'Widows' => ['Widows', '67d407be-9e57-4f33-a8b2-474b43913bd2', 'General'],
            'Sustainable Livelihood' => ['Sustainable Livelihood', 'c47c0f54-e2aa-4043-b647-fb4b316ec520', 'General'],
            'Sadaqah' => ['Sadaqah', '9f4793cc-9ed1-4f62-bf4b-c33f022d57c1', 'Sadaqah'],
            'Zakat' => ['Zakat', 'e1f07166-59f6-4797-9bd1-0d597b0aff74', 'Zakat'],
            'Sadaqah Jariyah' => ['Sadaqah Jariyah', '1afb59b9-be22-4063-a6a3-b136ee15e0c1', 'Sadaqah'],
            'Lillah' => ['Lillah', '6d50093d-7d09-4490-9ab6-8c6d5a13e4a4', 'Lilah'],
            'Aqiqah' => ['Aqiqah', 'd62dccf2-bf70-488b-a39f-73852aadeea8', 'Aqeeqah'],
            'Kaffarah' => ['Kaffarah', '247c2c21-72a2-4354-8c3c-8345264d5f3d', 'Kaffarah'],
            'Ramadan Calendar' => ['Ramadan Calendar', '647d7f7a-0536-4022-9d04-60e84ac73d6f', 'General'],
            'Ramadan Food Bags' => ['Ramadan Food Bags', 'e7618f81-853f-40d3-b0c1-fb377e107ffe', 'RamadanFoodPack'],
            'Ramadan Food Packs (x3)' => ['Ramadan Food Packs (x3)', 'e7618f81-853f-40d3-b0c1-fb377e107ffe', 'RamadanFoodPack'],
            'Seher & Iftar' => ['Seher & Iftar', '1f5776f6-8c19-4406-915f-aced73b5728b', 'SehrIftar'],
            'Fidya' => ['Fidya', '7252a505-4c3b-46e5-9c87-b214af9348e9', 'Fidya'],
            'Zakat ul Fitr' => ['Zakat ul Fitr', 'b40569dc-f697-4842-b8a9-f2fd245b449a', 'Fitrana'],
            'Eid Gifts' => ['Eid Gifts', '3ff3de40-d9c7-4845-8865-aec974a934c8', 'EidGifts'],
            'Zakat Calculator' => ['Zakat Calculator', '89bb2a73-4696-4047-b78a-25a81a26457b', 'Zakat'],
            'Qurbani' => ['Qurbani', '79ef0e31-b511-4092-93ac-55d049171ae3', 'Qurbani'],
            'Dhul Hajj' => ['Dhul Hajj', '9cffd318-5f82-430e-9e54-49bdfb813edd', 'General'],
            'Dhul Hajj Giving' => ['Dhul Hajj Giving', '1b3d41d6-764a-40ed-90b6-88e2a20cef62', 'General'],
            'Hajj 2027' => ['Hajj 2027', 'dfb57345-235f-41aa-ad5e-6bdf9eb8852c', 'General'],
            'Where Most Needed' => ['Where Most Needed', 'fedd5b15-da2b-4b96-b5ff-91c8257293f0', 'General'],
            'Zakat (Every Friday)' => ['Zakat (Every Friday)', '264563b6-6e0b-4a7f-b5e3-5ea7526766e3', 'Zakat'],
            'Sadaqah (Friday Giving)' => ['Sadaqah (Friday Giving)', '264563b6-6e0b-4a7f-b5e3-5ea7526766e3', 'Sadaqah'],
            'Education (Every Day)' => ['Education (Every Day)', '41ee9588-df9e-4d18-8770-76513400d842', 'Education'],
            'Orphans (Daily Giving)' => ['Orphans (Daily Giving)', '41ee9588-df9e-4d18-8770-76513400d842', 'General'],
            'Zakat (Every Month)' => ['Zakat (Every Month)', 'dfb26e6e-87b6-4c31-9ab2-7f6a02ed7091', 'Zakat'],
            'Sadaqah (Ramadan 30 Nights)' => ['Sadaqah (Ramadan 30 Nights)', 'c8e39cde-52b9-4f57-a7a9-7c5636a2828d', 'Sadaqah'],
            'Clothes for an Orphan' => ['Clothes for an Orphan', '88c7887f-e1b3-453f-a8eb-08e8e96dd863', 'General'],
            'Three Meals for an Orphan' => ['Three Meals for an Orphan', '88c7887f-e1b3-453f-a8eb-08e8e96dd863', 'General'],
            'Help Buy School Shoes for an Orphan' => ['Help Buy School Shoes for an Orphan', '88c7887f-e1b3-453f-a8eb-08e8e96dd863', 'General'],
            'Winter Blanket for a Widow or an Orphan' => ['Winter Blanket for a Widow or an Orphan', '67d407be-9e57-4f33-a8b2-474b43913bd2', 'General'],
            'Eid Gift for a Child or a Widow' => ['Eid Gift for a Child or a Widow', '3ff3de40-d9c7-4845-8865-aec974a934c8', 'EidGifts'],
            'Support a Widow' => ['Support a Widow', '67d407be-9e57-4f33-a8b2-474b43913bd2', 'General'],
            'Food Bag for a Family in Need' => ['Food Bag for a Family in Need', 'f2e2fc87-4ac0-436a-951d-0ce8ec9785fb', 'General'],
            'School Meals' => ['School Meals', '876082d6-1ea5-46e8-b351-7ec02537ff96', 'General'],
            'Youth Leadership & Community Development' => ['Youth Leadership & Community Development', 'fedd5b15-da2b-4b96-b5ff-91c8257293f0', 'General'],
            'Something new' => ['Something new', 'fedd5b15-da2b-4b96-b5ff-91c8257293f0', 'General'],
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
