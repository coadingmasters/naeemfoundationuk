<?php

/*
|--------------------------------------------------------------------------
| Ramadan automated giving
|--------------------------------------------------------------------------
|
| Drives the "Schedule Your Ramadan Giving" page. Dates are estimates and are
| subject to moon sighting — confirm them before each Ramadan.
|
*/

return [
    'nights' => 30,

    // Ramadan 1448 AH (estimated).
    'starts_at' => '2027-03-08',
    'ends_at' => '2027-04-06',

    // Daily amount presets (GBP).
    'amounts' => [3, 5, 10, 20],
    'popular' => 10,
    'default_daily' => 5,

    // Extra given on the 27th night (Laylatul Qadr), as a % of the daily amount.
    'boosts' => [0, 25, 50, 75, 100],

    'causes' => ['Zakat', 'Sadaqah', 'Orphans', 'Education'],
];
