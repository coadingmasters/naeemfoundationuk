<?php

namespace Tests\Feature;

use App\Http\Middleware\SetCountry;
use App\Support\Country;
use App\Support\RegionContext;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * One site, two domains: naeemfoundation.co.uk is the UK, naeemfoundation.ca is
 * Canada (CAD, its own phone/email/address/charity no./social links). Content is
 * shared, so region-owned data stays scoped to the content region (GB).
 *
 * DB-free: the middleware is run directly against a request for each domain.
 */
class DomainRegionTest extends TestCase
{
    private function visit(string $url, array $cookies = []): void
    {
        $request = Request::create($url, 'GET', [], $cookies);
        $this->app->instance('request', $request);

        app(SetCountry::class)->handle($request, fn () => response('ok'));
    }

    public function test_the_canadian_domain_gets_canadian_currency_and_details(): void
    {
        $this->visit('https://naeemfoundation.ca/');

        $this->assertSame('CA', Country::code());
        $this->assertSame('CAD', Country::get('currency'));
        $this->assertSame('CA$', Country::get('symbol'));
        $this->assertFalse(Country::get('gift_aid'));
        $this->assertSame('+1 437 333 4342', region('phone'));
        $this->assertSame('721376226RR0001', region('charity_no'));
        $this->assertSame('contact@naeemfoundation.ca', config('contact.email'));
        $this->assertSame('https://naeemfoundation.ca', config('contact.website'));
        $this->assertSame('1403 Hill Street', config('contact.address')[0]);
    }

    public function test_www_canadian_domain_is_canada_too(): void
    {
        $this->visit('https://www.naeemfoundation.ca/about');

        $this->assertSame('CA', Country::code());
    }

    public function test_the_uk_domain_and_unknown_hosts_stay_uk(): void
    {
        foreach (['https://naeemfoundation.co.uk/', 'http://localhost/', 'https://staging.example.test/'] as $url) {
            $this->visit($url);

            $this->assertSame('GB', Country::code(), $url);
            $this->assertSame('GBP', Country::get('currency'), $url);
            $this->assertSame('donate@naeemfoundation.co.uk', config('contact.email'), $url);
            $this->assertSame('+44 20 7078 8118', region('phone'), $url);
        }
    }

    public function test_content_is_shared_so_the_scope_stays_on_the_content_region(): void
    {
        $this->visit('https://naeemfoundation.ca/');

        $this->assertSame('GB', RegionContext::region());
        $this->assertFalse(RegionContext::isAll());
    }

    public function test_an_old_region_cookie_is_ignored(): void
    {
        $this->visit('https://naeemfoundation.co.uk/', [Country::COOKIE => 'CA']);
        $this->assertSame('GB', Country::code());

        $this->visit('https://naeemfoundation.ca/', [Country::COOKIE => 'US']);
        $this->assertSame('CA', Country::code());
    }

    public function test_social_icons_follow_the_domain(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $names = array_column(socials(), 'name');
        $this->assertSame(['Facebook', 'Instagram'], $names);
        $this->assertStringContainsString('naeemfoundationcanada', socials()[0]['url']);
    }

    public function test_the_header_shows_the_domains_own_phone_and_email(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $html = view('partials.header')->render();

        $this->assertStringContainsString('+1 437 333 4342', $html);
        $this->assertStringContainsString('contact@naeemfoundation.ca', $html);
        $this->assertStringNotContainsString('naeemfoundation.co.uk', $html);
        $this->assertStringNotContainsString('+44', $html);
    }

    public function test_each_domain_shows_only_its_own_bank_details(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $ca = view('partials.footer')->render();
        $this->assertSame(1, substr_count($ca, 'TD Canada Trust'));
        $this->assertStringContainsString('18685239071', $ca);
        $this->assertStringNotContainsString('Lloyds', $ca);
        $this->assertStringNotContainsString('13783063', $ca);
        $this->assertStringNotContainsString('30-54-66', $ca);
        $this->assertStringContainsString('+1 437 333 4342', $ca);
        $this->assertStringNotContainsString('naeemfoundation.co.uk', $ca);

        $this->visit('https://naeemfoundation.co.uk/');
        $uk = view('partials.footer')->render();
        $this->assertStringContainsString('Lloyds Bank', $uk);
        $this->assertStringContainsString('13783063', $uk);
        $this->assertStringContainsString('30-54-66', $uk);
        $this->assertStringNotContainsString('TD Canada Trust', $uk);
        $this->assertStringContainsString('donate@naeemfoundation.co.uk', $uk);
        $this->assertStringContainsString('+44 7960185682 : +44 7960185682', $uk);
    }

    public function test_the_privacy_page_bank_details_follow_the_domain(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $ca = view('privacy-policy')->render();
        $this->assertStringContainsString('TD Canada Trust', $ca);
        $this->assertStringNotContainsString('Lloyds', $ca);

        $this->visit('https://naeemfoundation.co.uk/');
        $uk = view('privacy-policy')->render();
        $this->assertStringContainsString('Lloyds Bank', $uk);
        $this->assertStringContainsString('13783063', $uk);
        $this->assertStringNotContainsString('TD Canada Trust', $uk);
    }

    public function test_the_fundraising_regulator_badge_is_uk_only(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $this->assertStringNotContainsString('Fundraising Regulator', view('partials.footer')->render());
        $this->assertStringNotContainsString('Fundraising Regulator', view('partials.payment-icons')->render());
        $this->assertStringContainsString('VISA', view('partials.payment-icons')->render());

        $this->visit('https://naeemfoundation.co.uk/');
        $this->assertStringContainsString('Fundraising Regulator', view('partials.footer')->render());
        $this->assertStringContainsString('Fundraising Regulator', view('partials.payment-icons')->render());
    }

    public function test_about_team_section_is_hidden_on_canada_only(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $this->assertStringNotContainsString('Talented Team Behind Our Services', view('about')->render());

        $this->visit('https://naeemfoundation.co.uk/');
        $this->assertStringContainsString('Talented Team Behind Our Services', view('about')->render());
    }

    public function test_careers_page_swaps_the_vacancy_for_volunteering_on_canada(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $ca = view('career', ['errors' => new \Illuminate\Support\ViewErrorBag])->render();
        $this->assertStringNotContainsString('Charitable Organization Manager', $ca);
        $this->assertStringNotContainsString('Fill Out The Form', $ca);
        $this->assertStringContainsString('Join as a Volunteer', $ca);
        $this->assertStringContainsString('/volunteer', $ca);

        $this->visit('https://naeemfoundation.co.uk/');
        $uk = view('career', ['errors' => new \Illuminate\Support\ViewErrorBag])->render();
        $this->assertStringContainsString('Charitable Organization Manager', $uk);
        $this->assertStringContainsString('Fill Out The Form', $uk);
    }

    public function test_annual_report_is_not_linked_or_served_on_canada(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $this->assertStringNotContainsString('Annual Report', view('partials.header')->render());
        $this->assertStringNotContainsString('Annual Report', view('partials.footer')->render());

        $this->visit('https://naeemfoundation.co.uk/');
        $this->assertStringContainsString('Annual Report', view('partials.header')->render());
        $this->assertStringContainsString('Annual Report', view('partials.footer')->render());
    }

    public function test_student_sponsorship_default_follows_the_domain(): void
    {
        $args = ['orphans' => collect(), 'errors' => new \Illuminate\Support\ViewErrorBag];

        $this->visit('https://naeemfoundation.ca/');
        $ca = view('education-sponsorships', $args)->render();
        $this->assertStringContainsString('data-monthly="100" data-yearly="1200"', $ca);
        $this->assertStringNotContainsString('data-monthly="53"', $ca);

        $this->visit('https://naeemfoundation.co.uk/');
        $uk = view('education-sponsorships', $args)->render();
        $this->assertStringContainsString('data-monthly="53" data-yearly="636"', $uk);
    }

    public function test_shop_is_not_linked_on_canada(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $this->assertStringNotContainsString('>Shop<', view('partials.header')->render());
        $this->assertStringNotContainsString('>Shop<', view('partials.footer')->render());

        $this->visit('https://naeemfoundation.co.uk/');
        $this->assertStringContainsString('Shop', view('partials.header')->render());
        $this->assertStringContainsString('>Shop<', view('partials.footer')->render());
    }

    public function test_shop_and_annual_report_routes_404_on_canada(): void
    {
        $this->get('https://naeemfoundation.ca/shop')->assertNotFound();
        $this->get('https://naeemfoundation.ca/shop/cart')->assertNotFound();
        $this->get('https://naeemfoundation.ca/annual-report')->assertNotFound();
    }

    public function test_no_uk_wording_on_canada_static_pages(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $errors = new \Illuminate\Support\ViewErrorBag;
        $banned = ['£', 'GBP', 'Gift Aid', 'UK taxpayer', 'in the UK', 'Welwyn', 'co.uk', '+44', 'Fundraising Regulator', 'Community Centre', 'Postcode', 'Lloyds', 'pounds', 'every pound', 'Every penny', 'London', 'GBP'];

        $rendered = 0;
        foreach (['privacy-policy', 'contact', 'schedule-ramadan-giving', 'schedule-friday-giving', 'ramadan-food-packs', 'zakat-ul-fitr', 'food-appeal', 'sadaqah', 'ramadan-calendar', 'partials.footer', 'partials.header'] as $name) {
            try {
                $html = view($name, ['errors' => $errors, 'orphans' => collect(), 'projects' => collect(), 'rows' => [], 'hasTimes' => false])->render();
            } catch (\Throwable $e) {
                continue; // needs data this DB-less test does not have
            }
            $rendered++;
            foreach ($banned as $word) {
                $this->assertStringNotContainsString($word, $html, "$name still shows \"$word\" on the Canada domain");
            }
        }
        $this->assertGreaterThanOrEqual(10, $rendered, 'too many pages could not be rendered to check');
    }

    public function test_community_centre_link_is_uk_only_in_the_footer(): void
    {
        $this->visit('https://naeemfoundation.ca/');
        $this->assertStringNotContainsString('Community Centre', view('partials.footer')->render());

        $this->visit('https://naeemfoundation.co.uk/');
        $this->assertStringContainsString('Community Centre', view('partials.footer')->render());
    }
}
