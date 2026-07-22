<?php

namespace App\Http\Middleware;

use App\Support\Country;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shares the visitor's active region (currency, phone, charity, Gift Aid) with
 * every view, and tells the layout whether to show the first-visit popup.
 */
class SetCountry
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('region', Country::current());
        View::share('regions', Country::all());
        View::share('regionChosen', Country::chosen());

        return $next($request);
    }
}
