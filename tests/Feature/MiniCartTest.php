<?php

namespace Tests\Feature;

use App\Support\DonationCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiniCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_renders_the_basket_with_a_hidden_badge_when_empty(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringContainsString('data-cart', $html);
        $this->assertStringContainsString('data-cart-count', $html);
        $this->assertStringContainsString('Your basket is empty', $html);
        // Badge is hidden while the basket has nothing in it.
        $this->assertMatchesRegularExpression('/nf-cart__badge[^"]*hidden/', $html);
    }

    public function test_header_badge_shows_the_item_count(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);
        $this->post(route('donate.add'), ['cause' => 'Sadaqah', 'amount' => 50]);

        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringContainsString('Zakat', $html);
        $this->assertStringContainsString('Sadaqah', $html);
        $this->assertStringContainsString('Complete Donation', $html);
        // Badge visible (no "hidden" class on it).
        $this->assertDoesNotMatchRegularExpression('/nf-cart__badge[^"]*hidden/', $html);
    }

    public function test_ajax_add_returns_the_refreshed_basket_instead_of_redirecting(): void
    {
        $response = $this->postJson(route('donate.add'), [
            'cause' => 'Zakat',
            'amount' => 250,
            'frequency' => 'one-off',
        ])->assertOk();

        $response->assertJsonStructure(['message', 'count', 'subtotal', 'html']);
        $this->assertSame(1, $response->json('count'));
        $this->assertSame(250, $response->json('subtotal'));
        $this->assertStringContainsString('Zakat', $response->json('html'));
        $this->assertStringContainsString('Complete Donation', $response->json('html'));
    }

    public function test_ajax_add_returns_validation_errors_as_json(): void
    {
        $this->postJson(route('donate.add'), ['cause' => 'Zakat', 'amount' => 0])
            ->assertStatus(422)
            ->assertJsonValidationErrors('amount');

        $this->assertTrue(DonationCart::isEmpty());
    }

    public function test_ajax_remove_returns_the_refreshed_basket(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);
        $id = DonationCart::items()[0]['id'];

        $response = $this->deleteJson(route('donate.remove', $id))->assertOk();

        $this->assertSame(0, $response->json('count'));
        $this->assertStringContainsString('Your basket is empty', $response->json('html'));
        $this->assertTrue(DonationCart::isEmpty());
    }

    public function test_non_ajax_add_still_redirects_to_checkout(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250])
            ->assertRedirect(route('donate.checkout'));
    }

    public function test_non_ajax_remove_still_redirects_to_checkout(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);
        $id = DonationCart::items()[0]['id'];

        $this->delete(route('donate.remove', $id))
            ->assertRedirect(route('donate.checkout'));
    }

    public function test_basket_panel_offers_a_link_to_the_donation_page(): void
    {
        $this->post(route('donate.add'), ['cause' => 'Zakat', 'amount' => 250]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('donate.checkout'), false);
    }
}
