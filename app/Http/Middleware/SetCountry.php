<?php

namespace App\Http\Middleware;

use App\Support\Country;
use App\Support\RegionContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shares the active region (currency, phone, email, address, charity, Gift Aid)
 * with every view. The region comes from the domain (.co.uk = UK, .ca = Canada),
 * so there is no chooser. Content is shared between domains: region-owned data
 * is scoped to `countries.content_region`, not to the domain's region. On admin
 * routes this is overridden later by the SetAdminRegion middleware.
 */
class SetCountry
{
    public function handle(Request $request, Closure $next): Response
    {
        // Same content on every domain; only currency and contact details differ.
        RegionContext::set((string) config('countries.content_region', 'GB'), false);

        $region = Country::current();

        // Outgoing emails, the contact page and the social icons read these.
        config([
            'contact.email' => $region['email'] ?? config('contact.email'),
            'contact.phone' => $region['phone'],
            'contact.mobile' => $region['mobile'] ?? config('contact.mobile'),
            'contact.address' => $region['address'] ?? config('contact.address'),
            'contact.charity_no' => $region['charity_no'],
            'contact.website' => $region['website'] ?? config('contact.website'),
        ]);

        if (isset($region['social'])) {
            foreach (['facebook', 'instagram', 'tiktok', 'x', 'youtube'] as $network) {
                config(['social.'.$network => $region['social'][$network] ?? '']);
            }
        }

        View::share('region', $region);
        View::share('regions', Country::all());

        return $next($request);
    }
}
