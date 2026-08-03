<?php

/*
|--------------------------------------------------------------------------
| "Giving" mega-menu structure
|--------------------------------------------------------------------------
|
| Drives both the header dropdown (resources/views/partials/header.blade.php)
| and the placeholder routes (routes/web.php), so they never fall out of sync.
| Each item links to its own 'route' if one exists, otherwise a placeholder
| page is auto-generated at /give/{slug} until the real page is built.
|
*/

return [

    'projects' => [
        'heading' => 'Projects',
        'items' => [
            ['title' => 'Education Sponsorships', 'route' => 'education-sponsorships'],
            ['title' => 'Food & Sustenance', 'route' => 'food-sustenance'],
            ['title' => 'Hostel for Students / Orphans', 'route' => 'hostel-for-students-orphans'],
            ['title' => 'Prosthetic Limb', 'route' => 'prosthetic-limb'],
            ['title' => 'Community Centre', 'route' => 'community-centre'],
            ['title' => 'Clean Water', 'route' => 'clean-water'],
            ['title' => 'Orphans Sponsorships', 'route' => 'orphans-sponsorships'],
            ['title' => 'Cambodia Education & Welfare', 'route' => 'cambodia-education-welfare'],
        ],
    ],

    'appeals' => [
        'heading' => 'Appeals',
        'items' => [
            ['title' => 'Food Appeal', 'route' => 'food-appeal'],
            ['title' => 'Healthcare', 'route' => 'healthcare'],
            ['title' => 'Water Pump', 'route' => 'water-well'],
            ['title' => 'Widows', 'route' => 'widows'],
            ['title' => 'Sustainable Livelihood', 'route' => 'sustainable-livelihood'],
        ],
    ],

    'islamic' => [
        'heading' => 'Islamic Giving',
        'items' => [
            ['title' => 'Sadaqah', 'route' => 'sadaqah'],
            ['title' => 'Zakat', 'route' => 'zakat'],
            ['title' => 'Sadaqah Jariyah', 'route' => 'sadaqah-jariyah'],
            ['title' => 'Lillah', 'route' => 'lillah'],
            ['title' => 'Aqiqah', 'route' => 'aqiqah'],
            ['title' => 'Kaffarah', 'route' => 'kaffarah'],
        ],
    ],

    'ramadan' => [
        'heading' => 'Ramadan Giving',
        'items' => [
            ['title' => 'Ramadan Calendar', 'route' => 'ramadan-calendar'],
            ['title' => 'Schedule Your Ramadan Giving', 'route' => 'schedule-ramadan-giving'],
            ['title' => 'Ramadan Food Bags', 'route' => 'ramadan-food-packs'],
            ['title' => 'Seher & Iftar', 'route' => 'sehri-iftar'],
            ['title' => 'Fidya', 'route' => 'fidya'],
            ['title' => 'Kaffarah', 'route' => 'kaffarah'],
            ['title' => 'Zakat ul Fitr', 'route' => 'zakat-ul-fitr'],
            ['title' => 'Eid Gifts', 'route' => 'eid-gifts'],
            ['title' => 'Zakat Calculator', 'route' => 'zakat-calculator'],
        ],
    ],

    'qurbani' => [
        'heading' => 'Qurbani',
        'items' => [
            ['title' => 'Qurbani', 'route' => 'qurbani'],
            ['title' => 'Dhul Hajj', 'route' => 'dhul-hajj'],
        ],
    ],

];
