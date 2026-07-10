<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Schema;
use Throwable;

class FoodSustenanceController extends Controller
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

        return view('food-sustenance', compact('projects'));
    }
}
