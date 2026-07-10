<?php

namespace Tests\Feature;

use App\Support\DonationCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Every "Donate" control on the site must lead somewhere real —
 * either a working form post or a live route, never href="#".
 */
class DonateButtonsTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_donate_call_to_action_is_a_dead_link(): void
    {
        $pages = [
            'home', 'about', 'history', 'careers', 'ask-mufti', 'zakat',
            'healthcare', 'food-sustenance', 'sustainable-livelihood',
            'cambodia-education-welfare', 'prosthetic-limb', 'water-well',
            'fidya', 'sadaqah', 'annual-report', 'schedule-ramadan-giving',
        ];

        foreach ($pages as $page) {
            $html = $this->get(route($page))->assertOk()->getContent();

            // Every anchor and the text inside it.
            preg_match_all('/<a\s[^>]*href="([^"]*)"[^>]*>(.*?)<\/a>/is', $html, $m, PREG_SET_ORDER);

            foreach ($m as [$_, $href, $inner]) {
                $text = strtolower(trim(strip_tags($inner)));

                $isDonateCta = str_contains($text, 'donate')
                    || str_contains($text, 'make a donation')
                    || str_contains($text, 'support the cause');

                if (! $isDonateCta) {
                    continue;
                }

                $this->assertNotSame(
                    '#',
                    $href,
                    "Page [{$page}] has a dead donate link with text \"{$text}\"."
                );
            }
        }
    }

    public function test_header_donate_button_points_at_the_checkout(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('donate.checkout'), false);
    }

    public function test_home_quick_donate_form_adds_to_the_basket(): void
    {
        $this->post(route('donate.add'), [
            'cause' => 'Where Most Needed',
            'amount' => 75,
            'frequency' => 'one-off',
            'image' => 'images/givezakat.png',
        ])->assertRedirect(route('donate.checkout'));

        $items = DonationCart::items();
        $this->assertCount(1, $items);
        $this->assertSame('Where Most Needed', $items[0]['cause']);
        $this->assertSame(75.0, $items[0]['amount']);
    }

    public function test_zakat_page_donate_widget_is_a_real_form(): void
    {
        $html = $this->get(route('zakat'))->assertOk()->getContent();

        $this->assertStringContainsString('action="'.route('donate.add').'"', $html);
        $this->assertStringContainsString('name="cause" value="Zakat"', $html);
        $this->assertStringContainsString('type="submit"', $html);
    }

    public function test_zakat_donation_can_be_added(): void
    {
        $this->post(route('donate.add'), [
            'cause' => 'Zakat',
            'amount' => 240,
            'frequency' => 'one-off',
        ])->assertRedirect(route('donate.checkout'));

        $this->assertSame(240.0, DonationCart::subtotal());
    }
}
