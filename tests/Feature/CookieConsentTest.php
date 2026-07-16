<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CookieConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_cookie_banner_is_present_on_every_page(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringContainsString('data-cookie', $html);
        $this->assertStringContainsString('We value your privacy', $html);
        $this->assertStringContainsString('data-cookie-accept', $html);
        $this->assertStringContainsString('data-cookie-reject', $html);
        $this->assertStringContainsString('Manage cookie preferences', $html);
    }

    public function test_the_footer_offers_a_way_to_reopen_cookie_settings(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Cookie Settings')
            ->assertSee('data-cookie-open', false);
    }

    public function test_the_cookie_banner_links_to_the_privacy_policy(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('privacy-policy'), false);
    }
}
