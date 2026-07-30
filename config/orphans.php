<?php

/*
|--------------------------------------------------------------------------
| Orphans available for sponsorship
|--------------------------------------------------------------------------
|
| Drives the "Sponsor an Orphan" grid (paginated over AJAX). Add a 'photo'
| path (in public/images/orphans/) to show a real portrait; otherwise a clean
| avatar placeholder is shown. Keep this list in sync with your records.
|
*/

$o = fn ($name, $location, $grade, $photo = null) => compact('name', 'location', 'grade', 'photo');

return [
    $o('Zubair Khasta-ur-Rahman', 'Karachi', 'Hifz'),
    $o('Uzair Afzal', 'Karachi', 'Hifz'),
    $o('Muhammad Haseeb', 'Punjab', 'Hifz'),
    $o('Ismail Fazal Khan', 'Balochistan', 'Gardan Hifz'),
    $o('Inayatullah Abdullah', 'Sindh', 'Hifz'),
    $o('Ibrahim Sardar Shah', 'Karachi', 'Gardan Hifz'),
    $o('Hamzah Javed', 'Karachi', 'Hifz'),
    $o('Adeel', 'Rahim Yar Khan', 'Hifz'),
    $o('Abdul Mannan Shafiq', 'Kaawadi', 'Hifz'),
    $o('Zakariya Muhammad', 'Karachi', '11th Grade'),
    $o('Taha Faseeh', 'Karachi', '10th Grade'),
    $o('Siraj Khuda Bakhsh', 'Khuzdar', '10th Grade'),
    $o('Shah Noor Ameen', 'Karachi', '10th Grade'),
    $o('Khurshid Hussain', 'Kohat', '10th Grade'),
    $o('Ikhtiyar', 'Karachi', '10th Grade'),
    $o('Ikhlaque', 'Karachi', '11th Grade'),
    $o('Zaman-ud-Deen', 'Afghanistan', 'Alim Course'),
    $o('Waliullah', 'Karachi', '2nd Year, Alim Course'),
    $o('Usman Akbar', 'Karachi', '7th Year, Alim Course'),
    $o('Tariq Jameel', 'Kohat', '6th Year, Alim Course'),
    $o('Syed Ahmad', 'Shangla', '7th Year, Alim Course'),
    $o('Qurban', 'Burma', '7th Year, Alim Course'),
    $o('Owais Khan', 'Peshawar', '6th Year, Alim Course'),
    $o('Naushad', 'Quetta', '6th Year, Alim Course'),
    $o('Najam Uddin Qureshi', 'Karachi', '7th Year, Alim Course'),
    $o('Naeem Khan', 'Karachi', '2nd Year, Alim Course'),
    $o('Muhammad', 'Karachi', '2nd Year, Alim Course'),
    $o('Manzoor Ahmed', 'Quetta', '7th Year, Alim Course'),
    $o('Mahmood Ul Hassan', 'Multan', '7th Year, Alim Course'),
    $o('Khawaja Zahid', 'Karachi', '7th Year, Alim Course'),
    $o('Ghulam Mustafa', 'Larkana', '7th Year, Alim Course'),
    $o('Danish Munir', 'Multan', '7th Year, Alim Course'),
    $o('Abdul Mannan', 'Sukkur', '7th Year, Alim Course'),
];
