<?php

/*
|--------------------------------------------------------------------------
| Friday (Jumu'ah) automated giving
|--------------------------------------------------------------------------
|
| Drives the "Schedule Your Friday Giving" page.
|
*/

return [
    // Amount presets (GBP).
    'amounts' => [3, 5, 10, 20],
    'popular' => 10,
    'default_amount' => 5,

    'causes' => ['Zakat', 'Sadaqah', 'Orphans', 'Education'],
];
