<?php

namespace App\Http\Controllers;

use App\Models\AnnualReport;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AnnualReportController extends Controller
{
    public function index()
    {
        // Resilient fetch so the page never breaks before migrations run.
        $reports = collect();

        try {
            if (Schema::hasTable('annual_reports')) {
                $reports = AnnualReport::active()->ordered()->get();
            }
        } catch (Throwable $e) {
            $reports = collect();
        }

        return view('annual-report', compact('reports'));
    }
}
