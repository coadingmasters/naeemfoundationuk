<?php

/*
|--------------------------------------------------------------------------
| Dhul Hijjah automated giving
|--------------------------------------------------------------------------
|
| Drives the "Automate Your Giving" panel on the Dhul Hajj page. Dates are
| estimates and are subject to moon sighting — confirm them before each
| Dhul Hijjah.
|
*/

return [
    'nights' => 10,

    // Dhul Hijjah 1448 AH (estimated) — the first ten blessed days, ending
    // on Eid al-Adha.
    'starts_at' => '2027-06-05',
    'ends_at' => '2027-06-14',

    // Daily amount presets (GBP).
    'amounts' => [5, 10, 20, 50],
    'popular' => 10,
    'default_daily' => 10,
];
