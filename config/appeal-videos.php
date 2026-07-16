<?php

/*
|--------------------------------------------------------------------------
| Appeal intro videos
|--------------------------------------------------------------------------
|
| Drives the "text left / video right" section under the hero on every Giving
| page. Swap `url` for your real footage — either a YouTube/Vimeo link
| (e.g. https://youtu.be/xxxx) or an uploaded file path (e.g. videos/zakat.mp4
| placed in public/videos/). `poster` is the still shown before play.
|
| Any page without an entry here falls back to 'default'.
|
*/

$sample = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';

return [
    'default' => [
        'url' => $sample,
        'poster' => 'images/changinslives1.jpg',
        'title' => 'See the impact of your donation',
    ],

    'zakat' => [
        'url' => $sample,
        'poster' => 'images/zakathero.png',
        'title' => 'How your Zakat changes lives',
    ],
    'sadaqah' => [
        'url' => $sample,
        'poster' => 'images/givesadqa.jpg',
        'title' => 'The power of Sadaqah',
    ],
    'fidya' => [
        'url' => $sample,
        'poster' => 'images/changinslives2.jpg',
        'title' => 'Understanding Fidya & Kaffarah',
    ],
    'sehri-iftar' => [
        'url' => $sample,
        'poster' => 'images/changinslives2.jpg',
        'title' => 'Sharing Sehri & Iftar',
    ],
    'zakat-ul-fitr' => [
        'url' => $sample,
        'poster' => 'images/givezakat.png',
        'title' => 'Zakat ul Fitr explained',
    ],
    'eid-gifts' => [
        'url' => $sample,
        'poster' => 'images/supporton.png',
        'title' => 'Bringing joy this Eid',
    ],
    'ramadan-food-packs' => [
        'url' => $sample,
        'poster' => 'images/changinslives2.jpg',
        'title' => 'Inside a Ramadan food pack',
    ],
    'food-sustenance' => [
        'url' => $sample,
        'poster' => 'images/changinslives2.jpg',
        'title' => 'Feeding families in need',
    ],
    'sustainable-livelihood' => [
        'url' => $sample,
        'poster' => 'images/supporton.png',
        'title' => 'Breaking the cycle of poverty',
    ],
    'healthcare' => [
        'url' => $sample,
        'poster' => 'images/changinslives4.jpg',
        'title' => 'Healthcare that reaches everyone',
    ],
    'cambodia-education-welfare' => [
        'url' => $sample,
        'poster' => 'images/changinslives1.jpg',
        'title' => 'Educating Cambodia’s future',
    ],
    'water-well' => [
        'url' => $sample,
        'poster' => 'images/handpump.jpg',
        'title' => 'Clean water, changed lives',
    ],
    'prosthetic-limb' => [
        'url' => $sample,
        'poster' => 'images/changinslives4.jpg',
        'title' => 'Helping someone walk again',
    ],
];
