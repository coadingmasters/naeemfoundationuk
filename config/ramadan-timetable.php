<?php

/*
|--------------------------------------------------------------------------
| Ramadan timetable (Seher & Iftar schedule)
|--------------------------------------------------------------------------
|
| The dates below drive the table on the Ramadan timetable page. Prayer times
| are intentionally left blank — fill in `times` from your OFFICIAL timetable.
| Never guess these values; people rely on them to open and close their fast.
|
| Format:  'YYYY-MM-DD' => ['fajr', 'sunrise', 'dhuhr', 'asr', 'maghrib', 'isha']
|
| Dates are subject to moon sighting.
|
*/

return [
    'year' => 2026,
    'location' => 'London, United Kingdom',

    'starts_at' => '2026-02-18', // 1st fast
    'ends_at' => '2026-03-19',   // 30th fast
    'eid_at' => '2026-03-20',    // Eid al-Fitr

    // Optional downloads. The button falls back to "print" when neither exists.
    'pdf' => 'downloads/ramadan-timetable-2026.pdf',

    'times' => [
        // '2026-02-18' => ['5:33', '7:10', '12:20', '14:48', '17:24', '18:46'],
    ],
];
