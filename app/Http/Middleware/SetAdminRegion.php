<?php

namespace App\Http\Middleware;

use App\Support\RegionContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the region scope for the admin panel (overrides the frontend cookie scope):
 *  - Region admin: locked to their own region.
 *  - Super admin:  the region they picked (session), or "all regions" by default.
 */
class SetAdminRegion
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isSuperAdmin()) {
            // Region admin — always scoped to their own region.
            RegionContext::set($user->region, false);
        } else {
            // Super admin — a chosen region, or "all regions" (aggregate).
            $chosen = $request->session()->get('admin_region');

            if ($chosen && array_key_exists($chosen, config('countries.list', []))) {
                RegionContext::set($chosen, false);
            } else {
                RegionContext::set(null, true);
            }
        }

        // Expose to admin views (topbar switcher, "adding as region X" hints, etc.).
        View::share('adminRegion', RegionContext::region());
        View::share('adminIsAll', RegionContext::isAll());
        View::share('adminIsSuper', (bool) ($user && $user->isSuperAdmin()));

        // A super admin viewing "all regions" can't create region-owned content —
        // they must pick a region first (otherwise the new row has no clear owner).
        if ($user && $user->isSuperAdmin() && RegionContext::isAll()) {
            $action = $request->route()?->getActionMethod();
            $name = (string) ($request->route()?->getName() ?? '');

            if (in_array($action, ['create', 'store'], true) && ! str_starts_with($name, 'admin.users')) {
                // Where to continue once they pick a region (the create page they wanted).
                $to = $request->isMethod('get') ? $request->url() : url()->previous();

                // Land on the resource index (never blocked) and show the region popup there.
                $indexName = preg_replace('/\.(create|store)$/', '.index', $name);
                $target = \Illuminate\Support\Facades\Route::has($indexName)
                    ? route($indexName)
                    : route('admin.dashboard');

                return redirect($target)->with('region_prompt', $to);
            }
        }

        return $next($request);
    }
}
