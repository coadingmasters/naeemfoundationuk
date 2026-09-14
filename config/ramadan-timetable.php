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
    'year' => 2027,
    'location' => 'London, United Kingdom',

    // PROVISIONAL — astronomical estimate only (lunar year runs ~10-11 days
    // earlier than 2026's), not yet confirmed by moon sighting. Replace with
    // the official dates as soon as they're announced.
    'starts_at' => '2027-02-08', // 1st fast
    'ends_at' => '2027-03-09',   // 30th fast
    'eid_at' => '2027-03-10',    // Eid al-Fitr

    // Optional downloads. The button falls back to "print" when neither exists.
    'pdf' => 'downloads/ramadan-timetable-2027.pdf',

    'times' => [
        // '2027-02-08' => ['5:33', '7:10', '12:20', '14:48', '17:24', '18:46'],
    ],
];
