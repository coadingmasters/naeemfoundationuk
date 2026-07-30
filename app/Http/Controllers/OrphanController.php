<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OrphanController extends Controller
{
    private const PER_PAGE = 12;

    /**
     * The "Sponsor an Orphan" page. Paginates the orphan list; on an AJAX
     * request it returns only the grid + pagination so the page never reloads.
     */
    public function index(Request $request)
    {
        $all = collect(config('orphans', []));
        $page = LengthAwarePaginator::resolveCurrentPage();

        $orphans = new LengthAwarePaginator(
            $all->forPage($page, self::PER_PAGE)->values(),
            $all->count(),
            self::PER_PAGE,
            $page,
            ['path' => route('orphans-sponsorships')]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return view('partials.orphan-list', compact('orphans'));
        }

        return view('orphans-sponsorships', compact('orphans'));
    }
}
