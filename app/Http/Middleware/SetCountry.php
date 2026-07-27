<?php

namespace App\Http\Middleware;

use App\Support\Country;
use App\Support\GeoLocation;
use App\Support\RegionContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shares the visitor's active region (currency, phone, charity, Gift Aid) with
 * every view, tells the layout whether to show the first-visit popup, and scopes
 * region-owned data to the visitor's chosen region. On admin routes this is
 * overridden later by the SetAdminRegion middleware.
 */
class SetCountry
{
    public function handle(Request $request, Closure $next): Response
    {
        // First visit (no chosen region yet): try to auto-detect it from the
        // visitor's location. If we cover their country, set it silently; if not
        // — or detection fails — we leave it, and the first-visit popup asks them.
        if (! Country::chosen()) {
            $detected = GeoLocation::region($request);

            if ($detected) {
                // Apply to this request now, and remember it for future visits.
                $request->cookies->set(Country::COOKIE, $detected);
                Cookie::queue(cookie()->forever(Country::COOKIE, $detected));
            }
        }

        // Frontend default: scope content + submissions to the visitor's region.
        RegionContext::set(Country::code(), false);

        View::share('region', Country::current());
        View::share('regions', Country::all());
        View::share('regionChosen', Country::chosen());

        return $next($request);
    }
}
