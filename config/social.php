<?php

/*
|--------------------------------------------------------------------------
| Social media profiles
|--------------------------------------------------------------------------
|
| One place for every social link on the site — the header top bar, the mobile
| drawer, the footer and the Contact page all read from here.
|
| Leave a value empty and its icon is hidden everywhere, so the site never
| shows an icon that goes nowhere. Fill one in and it appears in all four
| places at once.
|
| Set them here directly, or in .env (SOCIAL_FACEBOOK=..., etc.).
|
*/

return [

    'facebook' => env('SOCIAL_FACEBOOK', ''),

    'instagram' => env('SOCIAL_INSTAGRAM', ''),

    'tiktok' => env('SOCIAL_TIKTOK', ''),

    'x' => env('SOCIAL_X', ''),

    'youtube' => env('SOCIAL_YOUTUBE', ''),

];
