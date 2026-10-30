<?php

namespace Tests\Feature;

use App\Services\CrmDonations;
use App\Services\CrmException;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The CRM donation webhook client and the payment-step switch.
 * Deliberately database-free: nothing here touches a table.
 */
class CrmDonationsTest extends TestCase
{
    private const URL = 'https://crmbackend.rentpe.co.uk/api/webhooks/donations/cart';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'crm.enabled' => true,
            'crm.recurring' => true,
            'crm.webhook_url' => self::URL,
            'crm.branches.GBP' => ['api_key' => 'dwk_test_key', 'signing_secret' => 'dwsec_test_secret'],
            'crm.branches.USD' => ['api_key' => null, 'signing_secret' => null],
        ]);
    }

    private function donor(): array
    {
        return [
            'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com', 'phone' => '+447000000000',
            'billing_address' => '1 High Street', 'city' => 'Leeds', 'postcode' => 'LS1 1AA', 'gift_aid' => true,
        ];
    }

    private function mixedBasket(): array
    {
        return [
            ['id' => 'a', 'cause' => 'Zakat', 'amount' => 50, 'qty' => 1, 'frequency' => 'one-off', 'image' => 'images/givezakat.png'],
            ['id' => 'b', 'cause' => 'Sadaqah', 'amount' => 10, 'qty' => 2, 'frequency' => 'monthly', 'image' => 'images/givesadqa.jpg'],
            ['id' => 'c', 'cause' => 'Orphan Cloth', 'amount' => 5, 'qty' => 1, 'frequency' => 'weekly', 'image' => 'images/supporton.png'],
        ];
    }

    public function test_signature_is_hmac_sha256_of_timestamp_dot_body(): void
    {
        $expected = hash_hmac('sha256', '1700000000.{"a":1}', 'secret');

        $this->assertSame($expected, CrmDonations::sign('secret', 1700000000, '{"a":1}'));
        $this->assertSame(64, strlen($expected));
    }

    public function test_enabled_only_with_switch_and_credentials_for_that_currency(): void
    {
        $crm = new CrmDonations;

        $this->assertTrue($crm->enabledFor('GBP'));
        $this->assertTrue($crm->enabledFor('gbp'));
        $this->assertFalse($crm->enabledFor('USD'), 'No USD keys yet — USD must keep using PayPal.');
        $this->assertFalse($crm->enabledFor('CAD'));

        config(['crm.enabled' => false]);
        $this->assertFalse($crm->enabledFor('GBP'));
    }

    public function test_cart_reference_is_stable_for_an_exact_repeat_and_changes_when_anything_changes(): void
    {
        $crm = new CrmDonations;
        $ref = fn (array $donor, array $basket, string $donation = 'NF-ABC') => $crm->buildPayload($donor, $basket, $donation, 'https://x.test/t', 'United Kingdom')['cartReference'];

        $basket = $this->mixedBasket();
        $editedAmount = $basket;
        $editedAmount[0]['amount'] = 60;
        $editedDonor = $this->donor();
        $editedDonor['phone'] = '+447111111111';
        $noGiftAid = $this->donor();
        $noGiftAid['gift_aid'] = false;

        $this->assertSame($ref($this->donor(), $basket), $ref($this->donor(), $basket), 'A double-click must reuse the same cart.');
        $this->assertNotSame($ref($this->donor(), $basket), $ref($this->donor(), $editedAmount));
        $this->assertNotSame($ref($this->donor(), $basket), $ref($editedDonor, $basket), 'Changed donor details must not reuse the old cart (the CRM refuses that).');
        $this->assertNotSame($ref($this->donor(), $basket), $ref($noGiftAid, $basket));
        $this->assertLessThanOrEqual(64, strlen($ref($this->donor(), $basket, 'NF-ABCDEFGHIJ')), 'payment_id is varchar(64).');
    }

    #[DataProvider('campaigns')]
    public function test_cause_maps_to_the_right_campaign(string $cause, string $campaignId, string $type): void
    {
        $this->assertSame(['id' => $campaignId, 'type' => $type], (new CrmDonations)->campaignFor($cause));
    }

    public static function campaigns(): array
    {
        return [
            'zakat' => ['Zakat', 'e21d1288-021d-461b-9e87-5cee3e44ac19', 'Zakat'],
            'sadaqah' => ['Give Sadaqah', '9b7a5dda-930a-4092-8514-7c13b4ef548b', 'Sadaqah'],
            'zakat ul fitr is not plain zakat' => ['Zakat ul Fitr', '285251a7-ffd8-4160-b0a1-edfbd05c562e', 'Fitrana'],
            'water pump' => ['Water Handpump Share', '51d722b0-78d3-4d89-8847-60fcb93b1bae', 'General'],
            'binoria beats generic water' => ['Binoria Water Campaign', '0c386a0c-262d-4cdc-b5fe-98a9fe38fd59', 'General'],
            'food' => ['Emergency Food Pack', 'd3bdf595-1f96-4fa6-a268-64a698064cc6', 'General'],
            'fidya' => ['Fidya', '8a50340c-e938-4523-a824-35d94d377bad', 'Fidya'],
            'qurbani' => ['Qurbani 2026', '8da79768-f673-4ec6-8ba5-3d8bb16902b7', 'Qurbani'],
            'unmapped falls back to the general campaign (type must be one the CRM accepts)' => ['Where Most Needed', '1da40652-0671-44f7-a5a8-6b786d0413f7', 'General'],
        ];
    }

    public function test_mixed_basket_becomes_one_time_and_recurring_items(): void
    {
        $payload = (new CrmDonations)->buildPayload($this->donor(), $this->mixedBasket(), 'NF-ABC', 'https://example.test/donate/thank-you', 'United Kingdom');

        $this->assertSame('https://example.test/donate/thank-you', $payload['returnUrl']);
        $this->assertStringStartsWith('NF-ABC-', $payload['cartReference']);
        $this->assertSame(
            ['email' => 'jane@example.com', 'firstName' => 'Jane', 'lastName' => 'Doe', 'phoneNumber' => '+447000000000',
                'address' => '1 High Street, Leeds', 'postcode' => 'LS1 1AA', 'country' => 'United Kingdom', 'isGiftAid' => true],
            $payload['donor'],
        );

        $items = $payload['items'];
        $this->assertCount(3, $items);

        $this->assertSame('OneTime', $items[0]['frequency']);
        $this->assertSame(50.0, $items[0]['amount']);
        $this->assertArrayNotHasKey('interval', $items[0]);

        $this->assertSame('Monthly', $items[1]['frequency']);
        $this->assertSame(20.0, $items[1]['amount'], 'Quantity is folded into the amount.');
        $this->assertSame(1, $items[1]['interval']);

        $this->assertSame('Weekly', $items[2]['frequency']);
        $this->assertSame(1, $items[2]['interval']);

        $this->assertSame($payload['cartReference'].'-1', $items[0]['externalReference']);
        $this->assertSame('e21d1288-021d-461b-9e87-5cee3e44ac19', $items[0]['campaignId']);
    }

    public function test_lines_with_the_same_campaign_and_schedule_are_merged(): void
    {
        $basket = [
            ['cause' => 'Zakat', 'amount' => 10, 'qty' => 1, 'frequency' => 'one-off'],
            ['cause' => 'Zakat', 'amount' => 25, 'qty' => 2, 'frequency' => 'one-off'],
        ];

        $payload = (new CrmDonations)->buildPayload($this->donor(), $basket, 'NF-ABC', 'https://x.test/t', 'United Kingdom');

        $this->assertCount(1, $payload['items']);
        $this->assertSame(60.0, $payload['items'][0]['amount']);
    }

    public function test_more_than_ten_distinct_lines_is_refused(): void
    {
        $basket = array_map(fn ($cause) => ['cause' => $cause, 'amount' => 5, 'qty' => 1, 'frequency' => 'one-off'], [
            'Gaza', 'Flood', 'Cambodia', 'Prosthetic Limb', 'Binoria', 'Water Pump', 'Iftar', 'Ration', 'Food Pack', 'Health Care', 'Education',
        ]);

        $this->expectException(CrmException::class);

        (new CrmDonations)->buildPayload($this->donor(), $basket, 'NF-ABC', 'https://x.test/t', 'United Kingdom');
    }

    public function test_sending_the_same_cart_again_returns_the_same_checkout_link(): void
    {
        Http::fake([self::URL => Http::response([
            'cartId' => 'c1', 'cartReference' => 'x', 'status' => 'pending', 'duplicate' => true,
            'redirectUrl' => 'https://pay.example.test/checkout/xyz', 'redirectExpiresAt' => '2026-10-30T12:00:00Z',
        ], 200)]);

        $crm = new CrmDonations;
        $payload = $crm->buildPayload($this->donor(), $this->mixedBasket(), 'NF-ABC', 'https://x.test/t', 'United Kingdom');

        $this->assertSame('https://pay.example.test/checkout/xyz', $crm->createCart('GBP', $payload)['redirectUrl']);
    }

    public function test_create_cart_sends_a_correctly_signed_request_and_returns_the_checkout_link(): void
    {
        Http::fake([self::URL => Http::response([
            'cartId' => 'c1', 'cartReference' => 'NF-ABC-1', 'status' => 'pending',
            'redirectUrl' => 'https://pay.example.test/checkout/xyz', 'redirectExpiresAt' => '2026-10-30T12:00:00Z',
        ], 201)]);

        $crm = new CrmDonations;
        $payload = $crm->buildPayload($this->donor(), $this->mixedBasket(), 'NF-ABC', 'https://x.test/t', 'United Kingdom');
        $cart = $crm->createCart('GBP', $payload);

        $this->assertSame('https://pay.example.test/checkout/xyz', $cart['redirectUrl']);

        Http::assertSent(function (HttpRequest $request) use ($payload) {
            $timestamp = $request->header('X-Timestamp')[0];
            $body = $request->body();

            return $request->url() === self::URL
                && $request->method() === 'POST'
                && $request->header('X-Api-Key')[0] === 'dwk_test_key'
                && abs((int) $timestamp - time()) < 10
                && $request->header('X-Signature')[0] === 'sha256='.hash_hmac('sha256', $timestamp.'.'.$body, 'dwsec_test_secret')
                && json_decode($body, true) == $payload; // == : JSON turns 50.0 into 50
        });
    }

    public function test_create_cart_rejects_anything_but_201_or_an_insecure_checkout_link(): void
    {
        $crm = new CrmDonations;
        $payload = $crm->buildPayload($this->donor(), $this->mixedBasket(), 'NF-ABC', 'https://x.test/t', 'United Kingdom');

        Http::fake([self::URL => Http::response(['message' => 'returnUrl domain not allowed'], 422)]);

        try {
            $crm->createCart('GBP', $payload);
            $this->fail('A 422 must raise a CrmException.');
        } catch (CrmException $e) {
            $this->assertStringContainsString('422', $e->getMessage());
        }

        Http::fake([self::URL => Http::response(['redirectUrl' => 'http://insecure.test/x'], 201)]);
        $this->expectException(CrmException::class);
        $crm->createCart('GBP', $payload);
    }

    public function test_status_check_is_a_signed_get_on_the_cart_reference(): void
    {
        Http::fake([self::URL.'/*' => Http::response(['cartReference' => 'NF-ABC-1', 'status' => 'paid'], 200)]);

        $status = (new CrmDonations)->cartStatus('GBP', 'NF-ABC-12345678');

        $this->assertSame('paid', $status['status']);

        Http::assertSent(function (HttpRequest $request) {
            $timestamp = $request->header('X-Timestamp')[0];

            return $request->method() === 'GET'
                && $request->url() === self::URL.'/NF-ABC-12345678'
                && $request->header('X-Signature')[0] === 'sha256='.hash_hmac('sha256', $timestamp.'.NF-ABC-12345678', 'dwsec_test_secret');
        });
    }

    #[DataProvider('statuses')]
    public function test_status_words_are_normalised_and_unknown_is_never_paid(?string $raw, string $expected): void
    {
        $this->assertSame($expected, CrmDonations::normaliseStatus($raw));
    }

    public static function statuses(): array
    {
        return [
            ['paid', 'paid'], ['Completed', 'paid'], ['SUCCESS', 'paid'], ['succeeded', 'paid'],
            ['pending', 'pending'], ['processing', 'pending'], ['awaiting_payment', 'pending'],
            ['cancelled', 'cancelled'], ['canceled', 'cancelled'],
            ['failed', 'failed'], ['expired', 'failed'],
            ['something-new', 'unknown'], ['', 'unknown'], [null, 'unknown'],
        ];
    }

    // ------------------------------------------------------ payment step + start

    private function sessionBasket(): array
    {
        return [
            'donation' => [
                'reference' => 'NF-TESTREF123',
                'donor' => 'Jane Doe',
                'details' => $this->donor(),
            ],
            'donation_cart' => $this->mixedBasket(),
        ];
    }

    public function test_payment_step_offers_the_crm_button_and_no_paypal_when_enabled(): void
    {
        $response = $this->withSession($this->sessionBasket())->get(route('donate.payment'));

        $response->assertOk();
        $response->assertSee(route('donate.crm.start'), false);
        $response->assertSee('securely');
        $response->assertSee('repeats every month', false);
        $response->assertDontSee('cover_fee', false);
        $response->assertDontSee('paypal.com/sdk');
    }

    public function test_payment_step_still_uses_paypal_when_the_crm_is_off(): void
    {
        config(['crm.enabled' => false]);

        $response = $this->withSession($this->sessionBasket())->get(route('donate.payment'));

        $response->assertOk();
        $response->assertDontSee(route('donate.crm.start'), false);
        $response->assertSee('cover_fee', false);
    }

    public function test_recurring_baskets_stay_on_paypal_while_crm_recurring_is_off(): void
    {
        config(['crm.recurring' => false]);
        $crm = new CrmDonations;
        $oneTime = [['cause' => 'Zakat', 'amount' => 5, 'qty' => 1, 'frequency' => 'one-off']];

        $this->assertTrue($crm->enabledForBasket('GBP', $oneTime));
        $this->assertFalse($crm->enabledForBasket('GBP', $this->mixedBasket()));

        config(['crm.recurring' => true]);
        $this->assertTrue($crm->enabledForBasket('GBP', $this->mixedBasket()));

        config(['crm.enabled' => false, 'crm.recurring' => false]);
        $this->assertFalse($crm->enabledForBasket('GBP', $oneTime));
    }

    public function test_payment_step_and_start_use_paypal_for_a_recurring_basket_while_crm_recurring_is_off(): void
    {
        config(['crm.recurring' => false]);
        Http::fake();

        $this->withSession($this->sessionBasket())->get(route('donate.payment'))
            ->assertOk()
            ->assertDontSee(route('donate.crm.start'), false)
            ->assertSee('cover_fee', false);

        $this->withSession($this->sessionBasket())->post(route('donate.crm.start'))
            ->assertRedirect(route('donate.payment'));
        Http::assertNothingSent();
    }

    public function test_start_sends_the_basket_to_the_crm_and_redirects_the_donor_to_pay(): void
    {
        Http::fake([self::URL => Http::response([
            'cartId' => 'c1', 'cartReference' => 'x', 'status' => 'pending',
            'redirectUrl' => 'https://pay.example.test/checkout/xyz', 'redirectExpiresAt' => '2026-10-30T12:00:00Z',
        ], 201)]);

        $response = $this->withSession($this->sessionBasket())->post(route('donate.crm.start'));

        $response->assertRedirect('https://pay.example.test/checkout/xyz');

        Http::assertSent(function (HttpRequest $request) {
            $body = json_decode($request->body(), true);

            return $body['returnUrl'] === route('donate.thank-you')
                && count($body['items']) === 3
                && $body['donor']['email'] === 'jane@example.com';
        });
    }

    public function test_start_shows_a_friendly_error_and_charges_nothing_when_the_crm_refuses(): void
    {
        Http::fake([self::URL => Http::response(['message' => 'nope'], 500)]);

        $response = $this->withSession($this->sessionBasket())->post(route('donate.crm.start'));

        $response->assertRedirect(route('donate.payment'));
        $response->assertSessionHasErrors('crm');
    }

    public function test_start_does_nothing_when_the_crm_is_off(): void
    {
        config(['crm.enabled' => false]);
        Http::fake();

        $response = $this->withSession($this->sessionBasket())->post(route('donate.crm.start'));

        $response->assertRedirect(route('donate.payment'));
        Http::assertNothingSent();
    }

    // ------------------------------------------------------- private test link

    public function test_preview_link_turns_the_crm_on_for_that_browser_only_while_it_is_off_for_everyone(): void
    {
        config(['crm.enabled' => false, 'crm.preview_token' => 'secret-token-123']);

        // A normal visitor: PayPal, no CRM button.
        $this->withSession($this->sessionBasket())->get(route('donate.payment'))
            ->assertOk()->assertDontSee(route('donate.crm.start'), false);

        // The tester opens the private link...
        $this->get('/donate/crm-preview/secret-token-123')->assertRedirect(route('donate.make'));
        $this->assertTrue(session('crm_preview'));

        // ...and now sees the CRM checkout, with a test-mode notice.
        $this->withSession($this->sessionBasket() + ['crm_preview' => true])->get(route('donate.payment'))
            ->assertOk()
            ->assertSee(route('donate.crm.start'), false)
            ->assertSee('CRM test mode');
    }

    public function test_preview_link_is_a_404_with_the_wrong_token_or_when_no_token_is_set(): void
    {
        config(['crm.preview_token' => 'secret-token-123']);
        $this->get('/donate/crm-preview/wrong')->assertNotFound();
        $this->assertNull(session('crm_preview'));

        config(['crm.preview_token' => null]);
        $this->get('/donate/crm-preview/anything')->assertNotFound();
        $this->get('/donate/crm-preview/')->assertNotFound();
    }

    public function test_preview_link_can_be_switched_off_again(): void
    {
        config(['crm.preview_token' => 'secret-token-123']);

        $this->withSession(['crm_preview' => true])->get('/donate/crm-preview/secret-token-123/off')
            ->assertRedirect(route('donate.make'));

        $this->assertNull(session('crm_preview'));
    }

    public function test_preview_cannot_work_without_credentials(): void
    {
        config(['crm.enabled' => false, 'crm.preview_token' => 't', 'crm.branches.GBP' => ['api_key' => null, 'signing_secret' => null]]);

        $this->withSession($this->sessionBasket() + ['crm_preview' => true])->get(route('donate.payment'))
            ->assertOk()->assertDontSee(route('donate.crm.start'), false);
    }
}
