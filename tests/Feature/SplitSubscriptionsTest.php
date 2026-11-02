<?php

namespace Tests\Feature;

use App\Mail\DonationReceipt;
use App\Services\PayPal;
use App\Support\DonationCart;
use Illuminate\Support\Facades\Mail;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Two or more regular gifts in one basket are each set up as their OWN PayPal
 * subscription (so each can be cancelled alone), one at a time, with the basket's
 * one-off gifts charged once, with the first subscription.
 *
 * DB-free: PayPal is mocked and the donation row save is best-effort (it logs and
 * carries on, exactly as it does in production if the database hiccups).
 */
class SplitSubscriptionsTest extends TestCase
{
    private function basket(): array
    {
        return [
            ['id' => 'one', 'cause' => 'Zakat', 'amount' => 10, 'qty' => 1, 'frequency' => 'one-off', 'image' => 'x'],
            ['id' => 'm', 'cause' => 'Sadaqah', 'amount' => 5, 'qty' => 1, 'frequency' => 'monthly', 'image' => 'x'],
            ['id' => 'w', 'cause' => 'Water Well', 'amount' => 3, 'qty' => 1, 'frequency' => 'weekly', 'image' => 'x'],
        ];
    }

    private function sessionData(array $extra = []): array
    {
        return $extra + [
            'donation' => [
                'reference' => 'NF-SPLIT00001',
                'donor' => 'Jane Doe',
                'details' => ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com', 'gift_aid' => false],
            ],
            DonationCart::SESSION_KEY => $this->basket(),
        ];
    }

    public function test_only_two_or_more_regular_gifts_are_split(): void
    {
        $this->withSession($this->sessionData());
        $this->assertTrue(DonationCart::splitRecurring());
        $this->assertSame('m', DonationCart::nextSplitLine()['id']);

        session([DonationCart::SESSION_KEY => [$this->basket()[0], $this->basket()[1]]]);
        $this->assertFalse(DonationCart::splitRecurring(), 'One regular gift keeps the existing single-subscription flow.');
    }

    public function test_line_amount_includes_only_its_own_fee_share(): void
    {
        $this->assertSame(5.0, DonationCart::lineRecurringAmount(['amount' => 5, 'qty' => 1]));
        $this->assertSame(10.14, DonationCart::lineRecurringAmount(['amount' => 5, 'qty' => 2], true)); // 10 + 1.4%
    }

    public function test_gifts_are_set_up_one_at_a_time_and_the_one_off_part_is_charged_once(): void
    {
        Mail::fake();

        $this->mock(PayPal::class, function (MockInterface $m) {
            // 1st: the monthly gift, carrying the £10 one-off as the setup fee.
            $m->shouldReceive('ensureRecurringPlan')->once()->with(5.0, 'GBP', 'MONTH', 10.0)->andReturn('P-MONTHLY');
            // 2nd: the weekly gift, no setup fee.
            $m->shouldReceive('ensureRecurringPlan')->once()->with(3.0, 'GBP', 'WEEK', 0.0)->andReturn('P-WEEKLY');
            $m->shouldReceive('getSubscription')->with('I-MONTHLY')->andReturn(['plan_id' => 'P-MONTHLY', 'status' => 'ACTIVE']);
            $m->shouldReceive('getSubscription')->with('I-WEEKLY')->andReturn(['plan_id' => 'P-WEEKLY', 'status' => 'ACTIVE']);
        });

        $this->withSession($this->sessionData());

        // Out of order is refused, so the one-off can never be charged twice.
        $this->postJson(route('paypal.subscription.plan'), ['line' => 'w'])->assertStatus(422);

        $this->postJson(route('paypal.subscription.plan'), ['line' => 'm'])
            ->assertOk()->assertJson(['plan_id' => 'P-MONTHLY', 'reference' => 'NF-SPLIT00001']);

        $this->postJson(route('paypal.subscription.record'), ['line' => 'm', 'subscription_id' => 'I-MONTHLY'])
            ->assertOk()->assertJson(['redirect' => route('donate.payment')]);

        $this->assertSame('w', DonationCart::nextSplitLine()['id']);
        $this->assertFalse(DonationCart::isEmpty(), 'The basket stays until the last gift is set up.');

        $this->postJson(route('paypal.subscription.plan'), ['line' => 'w'])
            ->assertOk()->assertJson(['plan_id' => 'P-WEEKLY', 'reference' => 'NF-SPLIT00001-2']);

        $this->postJson(route('paypal.subscription.record'), ['line' => 'w', 'subscription_id' => 'I-WEEKLY'])
            ->assertOk()->assertJson(['redirect' => route('donate.thank-you')]);

        $this->assertTrue(DonationCart::isEmpty());
        $done = session('donation_completed');
        $this->assertSame(18.0, $done['total']);            // 5 + 10 one-off + 3
        $this->assertSame(8.0, $done['recurring_amount']);  // 5 + 3
        $this->assertSame(10.0, $done['one_off']);
        $this->assertSame(2, $done['gifts']);
        $this->assertTrue($done['mixed_frequencies']);

        // One receipt per subscription, each with its own reference.
        Mail::assertSent(DonationReceipt::class, 2);
        Mail::assertSent(DonationReceipt::class, fn ($mail) => $mail->reference === 'NF-SPLIT00001');
        Mail::assertSent(DonationReceipt::class, fn ($mail) => $mail->reference === 'NF-SPLIT00001-2');
    }

    public function test_a_subscription_for_another_plan_is_refused(): void
    {
        $this->mock(PayPal::class, function (MockInterface $m) {
            $m->shouldReceive('ensureRecurringPlan')->andReturn('P-MONTHLY');
            $m->shouldReceive('getSubscription')->andReturn(['plan_id' => 'P-SOMETHING-ELSE', 'status' => 'ACTIVE']);
        });

        $this->withSession($this->sessionData());
        $this->postJson(route('paypal.subscription.plan'), ['line' => 'm'])->assertOk();

        $this->postJson(route('paypal.subscription.record'), ['line' => 'm', 'subscription_id' => 'I-X'])->assertStatus(422);
        $this->assertFalse(DonationCart::isEmpty());
    }

    public function test_payment_step_shows_one_button_for_the_next_gift(): void
    {
        config(['crm.enabled' => false]);
        $this->mock(PayPal::class, fn (MockInterface $m) => $m->shouldReceive('isConfigured')->andReturn(false)->shouldReceive('clientId')->andReturn(null));

        $this->withSession($this->sessionData())->get(route('donate.payment'))
            ->assertOk()
            ->assertSee('Regular gift 1 of 2', false)
            ->assertSee('Sadaqah')
            ->assertSee('data-line="m"', false)
            ->assertDontSee('different schedules');
    }
}
