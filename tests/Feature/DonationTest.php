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

    public function test_submitting_details_stores_a_pending_donation_and_keeps_the_basket(): void
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
        ])->assertRedirect(route('donate.payment'));

        $donation = Donation::sole();

        $this->assertSame('ahsan@example.com', $donation->email);
        $this->assertTrue($donation->gift_aid);
        // The fee is chosen on the payment step, so the pending record has none yet.
        $this->assertFalse($donation->cover_fee);
        $this->assertSame('pending', $donation->status);
        $this->assertSame('300.00', $donation->subtotal);
        $this->assertSame('0.00', $donation->fee);
        $this->assertSame('300.00', $donation->total);
        $this->assertCount(2, $donation->items);
        $this->assertStringStartsWith('NF-', $donation->reference);

        // The basket survives until payment succeeds, so the donor can still edit it.
        $this->assertFalse(DonationCart::isEmpty());
    }

    public function test_covering_the_fee_on_the_payment_step_adds_it_to_the_total(): void
    {
        $this->reachPaymentStep(); // Zakat £250

        $this->post(route('donate.payment.process'), $this->cardPayload(['cover_fee' => 1]))
            ->assertRedirect(route('donate.thank-you'));

        $donation = Donation::sole();

        $this->assertTrue($donation->cover_fee);
        $this->assertSame('250.00', $donation->subtotal);
        $this->assertSame('3.50', $donation->fee);       // 1.4% of 250
        $this->assertSame('253.50', $donation->total);
    }

    public function test_fee_is_zero_when_the_donor_does_not_cover_it_at_payment(): void
    {
        $this->reachPaymentStep(); // Zakat £250

        // cover_fee unchecked (absent) but the hidden "present" marker is sent.
        $this->post(route('donate.payment.process'), $this->cardPayload(['cover_fee' => null]))
            ->assertRedirect(route('donate.thank-you'));

        $donation = Donation::sole();

        $this->assertFalse($donation->cover_fee);
        $this->assertSame('0.00', $donation->fee);
        $this->assertSame('250.00', $donation->total);
    }

    public function test_checkout_has_the_org_field_but_not_the_fee_or_add_ons(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 100]);

        $this->get(route('donate.checkout'))
            ->assertOk()
            ->assertSee('Organization Name', false)
            ->assertSee('data-org-toggle', false)
            // These moved to the payment step.
            ->assertDontSee('cover the transaction fee', false)
            ->assertDontSee('Want to add these', false);
    }

    public function test_organisation_name_is_required_when_donating_on_behalf(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 100]);

        $this->post(route('donate.store'), [
            'first_name' => 'Ahsan',
            'last_name' => 'Nawaz',
            'email' => 'ahsan@example.com',
            'phone' => '+441234567890',
            'billing_address' => '1 Test Street, London',
            'on_behalf_of_organisation' => 1,
        ])->assertSessionHasErrors('organisation_name');
    }

    public function test_organisation_name_is_stored(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 100]);

        $this->post(route('donate.store'), [
            'first_name' => 'Ahsan',
            'last_name' => 'Nawaz',
            'email' => 'ahsan@example.com',
            'phone' => '+441234567890',
            'billing_address' => '1 Test Street, London',
            'on_behalf_of_organisation' => 1,
            'organisation_name' => 'Acme Charity Ltd',
        ])->assertRedirect(route('donate.payment'));

        $donation = Donation::sole();

        $this->assertTrue($donation->on_behalf_of_organisation);
        $this->assertSame('Acme Charity Ltd', $donation->organisation_name);
    }

    public function test_payment_page_shows_the_fee_option_and_add_ons(): void
    {
        $this->reachPaymentStep();

        $this->get(route('donate.payment'))
            ->assertOk()
            ->assertSee('cover the transaction fee', false)
            ->assertSee('Want to add these', false)
            ->assertSee('Orphan Cloth');
    }

    public function test_a_payment_page_add_on_returns_to_the_payment_page(): void
    {
        $this->reachPaymentStep();

        $this->post(route('donate.add'), [
            'cause' => 'Family Meal',
            'amount' => 10,
            'frequency' => 'one-off',
            'redirect' => 'payment',
        ])->assertRedirect(route('donate.payment'));

        $this->assertSame(260.0, DonationCart::subtotal()); // 250 + 10
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

    public function test_quantity_can_be_increased_and_decreased(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 50]);
        $id = DonationCart::items()[0]['id'];

        $this->patch(route('donate.quantity', $id), ['qty' => 3])
            ->assertRedirect(route('donate.checkout'));
        $this->assertSame(3, DonationCart::items()[0]['qty']);
        $this->assertSame(150.0, DonationCart::subtotal());

        $this->patch(route('donate.quantity', $id), ['qty' => 2]);
        $this->assertSame(100.0, DonationCart::subtotal());
    }

    public function test_dropping_quantity_below_one_removes_the_line(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 50]);
        $id = DonationCart::items()[0]['id'];

        $this->patch(route('donate.quantity', $id), ['qty' => 0]);

        $this->assertTrue(DonationCart::isEmpty());
    }

    public function test_quantity_is_clamped_to_the_maximum(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 1]);
        $id = DonationCart::items()[0]['id'];

        // Above the allowed maximum is rejected by validation.
        $this->patch(route('donate.quantity', $id), ['qty' => 500])
            ->assertSessionHasErrors('qty');

        // The cap itself is accepted.
        $this->patch(route('donate.quantity', $id), ['qty' => DonationCart::MAX_QTY]);
        $this->assertSame(DonationCart::MAX_QTY, DonationCart::items()[0]['qty']);
    }

    public function test_editing_the_basket_after_submitting_details_changes_the_amount_charged(): void
    {
        $this->reachPaymentStep(); // Zakat £250 x1

        $id = DonationCart::items()[0]['id'];
        $this->patch(route('donate.quantity', $id), ['qty' => 2]); // now £500

        // The payment page reflects the live basket, not the old snapshot.
        $this->get(route('donate.payment'))
            ->assertOk()
            ->assertSee('507.00'); // 500 + 1.4% fee

        $this->post(route('donate.payment.process'), $this->cardPayload())
            ->assertRedirect(route('donate.thank-you'));

        $donation = Donation::sole();
        $this->assertSame('500.00', $donation->subtotal);
        $this->assertSame('507.00', $donation->total);
        $this->assertSame('paid', $donation->status);

        // Exactly one donation record — editing must not create a duplicate.
        $this->assertSame(1, Donation::count());
        $this->assertTrue(DonationCart::isEmpty());
    }

    public function test_payment_page_redirects_if_the_basket_was_emptied(): void
    {
        $this->reachPaymentStep();

        $id = DonationCart::items()[0]['id'];
        $this->delete(route('donate.remove', $id));

        $this->get(route('donate.payment'))
            ->assertRedirect(route('donate.checkout'))
            ->assertSessionHasErrors('cart');
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
        ]);
    }

    /** Card details plus the fee-cover choice made on the payment step. */
    private function cardPayload(array $overrides = []): array
    {
        return array_merge([
            'card_name' => 'Ahsan Nawaz',
            'card_number' => '4242 4242 4242 4242', // valid Luhn
            'expiry_month' => 12,
            'expiry_year' => (int) date('Y') + 2,
            'cvc' => '123',
            'cover_fee_present' => 1,
            'cover_fee' => 1,
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
