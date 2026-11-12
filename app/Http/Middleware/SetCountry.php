<?php

namespace App\Http\Middleware;

use App\Support\Country;
use App\Support\RegionContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shares the active region (currency, phone, charity, Gift Aid) with every view
 * and scopes region-owned data to it. The site is UK-only, so there is no
 * location detection and no region chooser. On admin routes this is
 * overridden later by the SetAdminRegion middleware.
 */
class SetCountry
{
    public function handle(Request $request, Closure $next): Response
    {
        // Frontend default: scope content + submissions to the visitor's region.
        RegionContext::set(Country::code(), false);

        View::share('region', Country::current());
        View::share('regions', Country::all());

        return $next($request);
    }
}
