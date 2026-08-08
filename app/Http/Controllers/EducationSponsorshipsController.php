<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EducationSponsorshipsController extends Controller
{
    public function index()
    {
        // Feeds the orphan carousel above the closing CTA. Resilient fetch so
        // the page never breaks before migrations have run on a fresh server —
        // the carousel simply renders nothing when the list is empty.
        $orphans = collect();

        try {
            if (Schema::hasTable('orphans')) {
                $orphans = Orphan::active()->ordered()->take(12)->get();
            }
        } catch (Throwable $e) {
            $orphans = collect();
        }

        return view('education-sponsorships', compact('orphans'));
    }
}
