<?php

namespace App\Http\Controllers\Concerns;

trait AssignsSortOrder
{
    /**
     * Work out the next sort order for a new record.
     *
     * Fills the lowest gap left by a deleted item first (e.g. orders
     * 1,2,3,5 → returns 4), otherwise places it after the highest
     * (e.g. 1,2,3,4 → returns 5). Returns 1 when the table is empty.
     */
    protected function nextSortOrder(string $modelClass): int
    {
        $used = $modelClass::query()->pluck('sort_order')->map(fn ($v) => (int) $v)->all();

        if (empty($used)) {
            return 1;
        }

        $set = array_flip($used);
        $max = max($used);

        for ($i = 1; $i <= $max; $i++) {
            if (! isset($set[$i])) {
                return $i; // first gap
            }
        }

        return $max + 1;
    }
}
