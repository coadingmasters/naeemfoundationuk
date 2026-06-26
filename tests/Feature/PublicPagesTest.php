<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public static function pageProvider(): array
    {
        return [
            'home' => ['home'],
            'about' => ['about'],
            'careers' => ['careers'],
            'history' => ['history'],
            'annual-report' => ['annual-report'],
            'news' => ['news'],
            'privacy-policy' => ['privacy-policy'],
            'zakat' => ['zakat'],
        ];
    }

    #[DataProvider('pageProvider')]
    public function test_public_page_renders(string $routeName): void
    {
        $this->get(route($routeName))->assertOk();
    }

    public function test_who_we_are_dropdown_links_are_present_in_header(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Who We Are');
        $response->assertSee('About Us');
        $response->assertSee('Annual Report');
        $response->assertSee('News &amp; Press', false);
    }
}
