<?php

/*
|--------------------------------------------------------------------------
| "Giving" mega-menu structure
|--------------------------------------------------------------------------
|
| Drives both the header dropdown (resources/views/partials/header.blade.php)
| and the placeholder routes (routes/web.php), so they never fall out of sync.
| Each item links to its own 'route' if one exists, otherwise a placeholder
| page is auto-generated at /give/{slug}.
|
*/

return [
    'appeals' => [
        'heading' => 'Appeals',
        'items' => [
            ['title' => 'Food & Sustenance', 'slug' => 'food-sustenance'],
            ['title' => 'Sustainable Livelihood', 'slug' => 'sustainable-livelihood'],
            ['title' => 'Healthcare', 'slug' => 'healthcare'],
            ['title' => 'Cambodia Education & Welfare', 'slug' => 'cambodia-education-welfare'],
            ['title' => 'Water Well', 'route' => 'water-well'],
            ['title' => 'Prosthetic Limb', 'slug' => 'prosthetic-limb'],
        ],
    ],

    'islamic' => [
        'heading' => 'Islamic Giving',
        'items' => [
            ['title' => 'Zakat', 'route' => 'zakat'],
            ['title' => 'Sadaqah', 'route' => 'sadaqah'],
            ['title' => 'Fidya & Kaffarah', 'route' => 'fidya'],
            ['title' => 'Sehri & Iftar', 'route' => 'sehri-iftar'],
            ['title' => 'Zakat ul Fitr', 'route' => 'zakat-ul-fitr'],
            ['title' => 'Eid Gifts for Children', 'route' => 'eid-gifts'],
        ],
        // Highlighted Ramadan calls-to-action shown at the bottom of the tab.
        'featured' => [
            ['title' => 'Schedule Your Ramadan Giving', 'slug' => 'schedule-ramadan-giving'],
            ['title' => 'Give Ramadan Food Packs', 'route' => 'ramadan-food-packs'],
        ],
    ],
];
