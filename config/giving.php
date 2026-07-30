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
            ['title' => 'Clean Water', 'slug' => 'clean-water'],
            ['title' => 'Orphans Sponsorships', 'slug' => 'orphans-sponsorships'],
            ['title' => 'Cambodia Education & Welfare', 'route' => 'cambodia-education-welfare'],
        ],
    ],

    'appeals' => [
        'heading' => 'Appeals',
        'items' => [
            ['title' => 'Food Appeal', 'slug' => 'food-appeal'],
            ['title' => 'Healthcare', 'route' => 'healthcare'],
            ['title' => 'Water Pump', 'slug' => 'water-pump'],
            ['title' => 'Widows', 'slug' => 'widows'],
            ['title' => 'Sustainable Livelihood', 'route' => 'sustainable-livelihood'],
        ],
    ],

    'islamic' => [
        'heading' => 'Islamic Giving',
        'items' => [
            ['title' => 'Sadaqah', 'route' => 'sadaqah'],
            ['title' => 'Zakat', 'route' => 'zakat'],
            ['title' => 'Sadaqah Jariyah', 'slug' => 'sadaqah-jariyah'],
            ['title' => 'Lillah', 'slug' => 'lillah'],
            ['title' => 'Aqiqah', 'slug' => 'aqiqah'],
            ['title' => 'Kaffarah', 'slug' => 'kaffarah'],
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
            ['title' => 'Kaffarah', 'slug' => 'kaffarah'],
            ['title' => 'Zakat ul Fitr', 'route' => 'zakat-ul-fitr'],
            ['title' => 'Eid Gifts', 'route' => 'eid-gifts'],
            ['title' => 'Zakat Calculator', 'route' => 'zakat-calculator'],
        ],
    ],

];
