<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SadaqahController extends Controller
{
    public function index()
    {
        // Resilient fetch so the page never breaks before migrations run.
        $projects = collect();

        try {
            if (Schema::hasTable('projects')) {
                $projects = Project::active()->ordered()->get();
            }
        } catch (Throwable $e) {
            $projects = collect();
        }

        return view('sadaqah', compact('projects'));
    }
}
