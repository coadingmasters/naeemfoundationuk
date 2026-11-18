<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 404s a route when the current domain's region has the named feature switched
 * off in config/countries.php (e.g. the Shop on naeemfoundation.ca).
 * Usage: ->middleware('region.feature:show_shop')
 */
class RequireRegionFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        abort_unless(region($feature, false), 404);

        return $next($request);
    }
}
