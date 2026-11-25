<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The site spells it "Healthcare" (one word). Any cause, appeal or project
 * that was typed in the admin as "Health Care" is joined up.
 */
return new class extends Migration
{
    private const COLUMNS = [
        'causes' => ['title', 'description'],
        'appeals' => ['title', 'description'],
        'projects' => ['title', 'description'],
    ];

    private const SWAPS = [
        'Health Care' => 'Healthcare',
        'Health care' => 'Healthcare',
        'health care' => 'healthcare',
        'HEALTH CARE' => 'HEALTHCARE',
        'Health-Care' => 'Healthcare',
        'Health-care' => 'Healthcare',
        'health-care' => 'healthcare',
    ];

    public function up(): void
    {
        foreach (self::COLUMNS as $table => $columns) {
            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                foreach (self::SWAPS as $from => $to) {
                    DB::table($table)
                        ->where($column, 'like', '%'.$from.'%')
                        ->update([$column => DB::raw('REPLACE(`'.$column.'`, '.DB::getPdo()->quote($from).', '.DB::getPdo()->quote($to).')')]);
                }
            }
        }
    }

    public function down(): void
    {
        // Spelling clean-up only; nothing to undo.
    }
};
