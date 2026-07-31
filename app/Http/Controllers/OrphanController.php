<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use Illuminate\Http\Request;

class OrphanController extends Controller
{
    /** The "Sponsor an Orphan" grid, with AJAX pagination. */
    public function index(Request $request)
    {
        $orphans = Orphan::active()->ordered()->paginate(12);

        // AJAX page changes only need the swapped-in grid, not the whole page.
        if ($request->ajax() || $request->wantsJson()) {
            return view('partials.orphan-list', compact('orphans'));
        }

        return view('orphans-sponsorships', compact('orphans'));
    }

    /** A single orphan's profile + a donation widget scoped to them. */
    public function show(Orphan $orphan)
    {
        abort_unless($orphan->is_active, 404);

        return view('orphans.show', compact('orphan'));
    }
}
