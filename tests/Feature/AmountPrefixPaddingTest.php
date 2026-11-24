<?php

namespace Tests\Feature;

use App\Http\Middleware\SetCountry;
use Illuminate\Http\Request;
use Tests\TestCase;

class AmountPrefixPaddingTest extends TestCase
{
    private function visit(string $url): void
    {
        $request = Request::create($url, 'GET');
        $this->app->instance('request', $request);

        app(SetCountry::class)->handle($request, fn () => response('ok'));
    }


    public function test_long_symbol_gets_wide_left_padding_in_make_donation_form(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $this->assertSame('CA$', region('symbol'));
        $html = view('donate.make', ['errors' => new \Illuminate\Support\ViewErrorBag])->render();
        $this->assertStringContainsString('pl-12 pr-3 text-base', $html);
        $this->assertStringNotContainsString('pl-7 pr-3 text-base', $html);
    }

    public function test_short_symbol_keeps_original_padding(): void
    {
        $this->visit('https://naeemfoundation.co.uk/');
        $html = view('donate.make', ['errors' => new \Illuminate\Support\ViewErrorBag])->render();
        $this->assertStringContainsString('pl-7 pr-3 text-base', $html);
    }
}
