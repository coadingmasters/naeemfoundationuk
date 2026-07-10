<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Support\DonationCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_loads_when_basket_is_empty(): void
    {
        $this->get(route('donate.checkout'))
            ->assertOk()
            ->assertSee('Complete Contribution')
            ->assertSee('Your contribution is empty');
    }

    public function test_a_donation_can_be_added_to_the_basket(): void
    {
        $this->post(route('donate.add'), [
            'cause' => 'Zakat',
            'amount' => 250,
            'frequency' => 'one-off',
        ])->assertRedirect(route('donate.checkout'));

        $this->get(route('donate.checkout'))
            ->assertOk()
            ->assertSee('Zakat')
            ->assertSee('250.00');
    }

    public function test_adding_the_same_cause_twice_increases_the_quantity(): void
    {
        $payload = ['cause' => 'Sadaqah', 'amount' => 50, 'frequency' => 'one-off'];

        $this->post(route('donate.add'), $payload);
        $this->post(route('donate.add'), $payload);

        $items = DonationCart::items();

        $this->assertCount(1, $items);
        $this->assertSame(2, $items[0]['qty']);
        $this->assertSame(100.0, DonationCart::subtotal());
    }

    public function test_totals_and_fee_are_calculated_correctly(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);
        $this->post(route('donate.add'), ['cause' => 'Sadaqah', 'amount' => 50]);

        $this->assertSame(300.0, DonationCart::subtotal());
        $this->assertSame(4.20, DonationCart::fee());            // 1.4% of 300
        $this->assertSame(304.20, DonationCart::total(true));    // fee covered
        $this->assertSame(300.0, DonationCart::total(false));    // fee not covered
    }

    public function test_an_item_can_be_removed_from_the_basket(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);
        $id = DonationCart::items()[0]['id'];

        $this->delete(route('donate.remove', $id))
            ->assertRedirect(route('donate.checkout'));

        $this->assertTrue(DonationCart::isEmpty());
    }

    public function test_adding_a_donation_requires_a_valid_amount(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 0])
            ->assertSessionHasErrors('amount');

        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 'abc'])
            ->assertSessionHasErrors('amount');
    }

    public function test_checkout_requires_donor_details(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);

        $this->post(route('donate.store'), [])
            ->assertSessionHasErrors(['first_name', 'last_name', 'email', 'phone', 'billing_address']);
    }

    public function test_checkout_rejects_an_empty_basket(): void
    {
        $this->post(route('donate.store'), [
            'first_name' => 'Ahsan',
            'last_name' => 'Nawaz',
            'email' => 'a@example.com',
            'phone' => '+441234567890',
            'billing_address' => '1 Test Street, London',
        ])->assertSessionHasErrors('cart');

        $this->assertSame(0, Donation::count());
    }

    public function test_a_complete_donation_is_persisted_and_clears_the_basket(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);
        $this->post(route('donate.add'), ['cause' => 'Sadaqah', 'amount' => 50]);

        $this->post(route('donate.store'), [
            'first_name' => 'Ahsan',
            'last_name' => 'Nawaz',
            'email' => 'ahsan@example.com',
            'phone' => '+441234567890',
            'billing_address' => '1 Test Street, London',
            'gift_aid' => 1,
            'cover_fee' => 1,
        ])->assertRedirect(route('donate.payment'));

        $donation = Donation::sole();

        $this->assertSame('ahsan@example.com', $donation->email);
        $this->assertTrue($donation->gift_aid);
        $this->assertTrue($donation->cover_fee);
        $this->assertSame('300.00', $donation->subtotal);
        $this->assertSame('4.20', $donation->fee);
        $this->assertSame('304.20', $donation->total);
        $this->assertCount(2, $donation->items);
        $this->assertStringStartsWith('NF-', $donation->reference);

        // Basket is emptied after a successful submission.
        $this->assertTrue(DonationCart::isEmpty());
    }

    public function test_fee_is_zero_when_the_donor_does_not_cover_it(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 100]);

        $this->post(route('donate.store'), [
            'first_name' => 'Ahsan',
            'last_name' => 'Nawaz',
            'email' => 'ahsan@example.com',
            'phone' => '+441234567890',
            'billing_address' => '1 Test Street, London',
        ])->assertRedirect(route('donate.payment'));

        $donation = Donation::sole();

        $this->assertSame('0.00', $donation->fee);
        $this->assertSame('100.00', $donation->total);
    }

    public function test_payment_page_redirects_without_a_reference(): void
    {
        $this->get(route('donate.payment'))->assertRedirect(route('donate.checkout'));
    }

    public function test_payment_page_shows_the_summary_after_checkout(): void
    {
        $this->reachPaymentStep();

        $this->get(route('donate.payment'))
            ->assertOk()
            ->assertSee('Payment Summary')
            ->assertSee('Add Payment Details')
            ->assertSee('Zakat')
            ->assertSee(Donation::sole()->reference);
    }

    public function test_payment_requires_all_card_fields(): void
    {
        $this->reachPaymentStep();

        $this->post(route('donate.payment.process'), [])
            ->assertSessionHasErrors(['card_name', 'card_number', 'expiry_month', 'expiry_year', 'cvc']);
    }

    public function test_payment_rejects_a_card_number_failing_the_luhn_check(): void
    {
        $this->reachPaymentStep();

        $this->post(route('donate.payment.process'), $this->cardPayload([
            'card_number' => '4242 4242 4242 4241', // last digit broken
        ]))->assertSessionHasErrors('card_number');

        $this->assertSame('pending', Donation::sole()->status);
    }

    public function test_payment_rejects_an_expired_card(): void
    {
        $this->reachPaymentStep();

        $lastMonth = now()->subMonth();

        $response = $this->post(route('donate.payment.process'), $this->cardPayload([
            'expiry_month' => $lastMonth->month,
            'expiry_year' => $lastMonth->year,
        ]));

        // In January "last month" falls into the previous year, which the year
        // rule rejects; otherwise the month rule catches it.
        $response->assertSessionHasErrors(
            $lastMonth->year < (int) date('Y') ? 'expiry_year' : 'expiry_month'
        );

        $this->assertSame('pending', Donation::sole()->status);
    }

    public function test_card_number_and_cvc_are_never_flashed_back_to_the_session(): void
    {
        $this->reachPaymentStep();

        $this->post(route('donate.payment.process'), $this->cardPayload([
            'card_number' => '4242 4242 4242 4241', // triggers a validation failure
        ]))->assertSessionHasErrors('card_number');

        $this->assertNull(session('_old_input.card_number'));
        $this->assertNull(session('_old_input.cvc'));
        $this->assertSame('Ahsan Nawaz', session('_old_input.card_name'));
    }

    public function test_a_valid_card_completes_the_donation(): void
    {
        $this->reachPaymentStep();

        $this->post(route('donate.payment.process'), $this->cardPayload())
            ->assertRedirect(route('donate.thank-you'));

        $this->assertSame('paid', Donation::sole()->status);
        $this->assertNull(session('donation'));
    }

    public function test_thank_you_page_redirects_when_no_donation_was_completed(): void
    {
        $this->get(route('donate.thank-you'))->assertRedirect(route('donate.checkout'));
    }

    public function test_thank_you_page_shows_the_reference_and_total(): void
    {
        $this->reachPaymentStep();
        $this->post(route('donate.payment.process'), $this->cardPayload());

        $this->get(route('donate.thank-you'))
            ->assertOk()
            ->assertSee('Thank You for Your Support', false)
            ->assertSee('Successful')
            ->assertSee(Donation::sole()->reference);
    }

    /** Add a donation and submit donor details so the payment step is reachable. */
    private function reachPaymentStep(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);

        $this->post(route('donate.store'), [
            'first_name' => 'Ahsan',
            'last_name' => 'Nawaz',
            'email' => 'ahsan@example.com',
            'phone' => '+441234567890',
            'billing_address' => '1 Test Street, London',
            'cover_fee' => 1,
        ]);
    }

    /** @return array<string, mixed> */
    private function cardPayload(array $overrides = []): array
    {
        return array_merge([
            'card_name' => 'Ahsan Nawaz',
            'card_number' => '4242 4242 4242 4242', // valid Luhn
            'expiry_month' => 12,
            'expiry_year' => (int) date('Y') + 2,
            'cvc' => '123',
        ], $overrides);
    }

    public function test_healthcare_page_loads(): void
    {
        $this->get(route('healthcare'))
            ->assertOk()
            ->assertSee('Health is a Basic')
            ->assertSee('Our focus');
    }
}
