<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Throwable;

class RamadanTimetableController extends Controller
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

        $rows = $this->rows();

        return view('schedule-ramadan-giving', [
            'projects' => $projects,
            'rows' => $rows,
            // True once at least one day has real prayer times configured.
            'hasTimes' => collect($rows)->contains(fn ($row) => $row['times'] !== null),
        ]);
    }

    /**
     * One row per fast, plus a final Eid row. Times come from config and are
     * null until the official timetable has been entered.
     *
     * @return array<int, array<string, mixed>>
     */
    private function rows(): array
    {
        $times = config('ramadan-timetable.times', []);

        $start = Carbon::parse(config('ramadan-timetable.starts_at'));
        $end = Carbon::parse(config('ramadan-timetable.ends_at'));
        $eid = Carbon::parse(config('ramadan-timetable.eid_at'));

        $rows = [];
        $day = 1;

        for ($date = $start->copy(); $date->lte($end); $date->addDay(), $day++) {
            $rows[] = [
                'date' => $date->copy(),
                'label' => $day === 1 ? '1st Ramadan' : str_pad((string) $day, 2, '0', STR_PAD_LEFT),
                'times' => $times[$date->toDateString()] ?? null,
            ];
        }

        $rows[] = [
            'date' => $eid,
            'label' => 'Eid al-Fitr',
            'times' => $times[$eid->toDateString()] ?? null,
        ];

        return $rows;
    }
}
