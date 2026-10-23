<?php

/*
|--------------------------------------------------------------------------
| Regions / countries the site operates in
|--------------------------------------------------------------------------
| The visitor picks one on first visit (see the country popup). The choice
| is stored in a cookie and drives the currency, phone number, charity
| registration number and Gift Aid availability across the whole site.
|
| Content stays English everywhere — only these region-specific values change.
*/

return [
    // Fallback used until the visitor chooses a region.
    'default' => 'GB',

    'list' => [
        'GB' => [
            'code' => 'GB',
            'name' => 'United Kingdom',
            'short' => 'UK',
            'flag' => '🇬🇧',
            'currency' => 'GBP',
            'symbol' => '£',
            'locale' => 'en_GB',
            'dial' => '+44',
            'phone' => '+44 20 7078 8118',
            'charity_no' => '1199466',
            'charity_label' => 'Registered Charity No.',
            'gift_aid' => true,
        ],
        'US' => [
            'code' => 'US',
            'name' => 'United States',
            'short' => 'US',
            'flag' => '🇺🇸',
            'currency' => 'USD',
            'symbol' => '$',
            'locale' => 'en_US',
            'dial' => '+1',
            // Same donation line for every region (no separate US/Canada office).
            'phone' => '+44 20 7078 8118',
            'charity_no' => '1199466',
            'charity_label' => 'UK Registered Charity No.',
            'gift_aid' => false, // Gift Aid is a UK-only tax relief.
        ],
        'CA' => [
            'code' => 'CA',
            'name' => 'Canada',
            'short' => 'Canada',
            'flag' => '🇨🇦',
            'currency' => 'CAD',
            'symbol' => 'CA$',
            'locale' => 'en_CA',
            'dial' => '+1',
            // Same donation line for every region (no separate US/Canada office).
            'phone' => '+44 20 7078 8118',
            'charity_no' => '1199466',
            'charity_label' => 'UK Registered Charity No.',
            'gift_aid' => false, // Gift Aid is a UK-only tax relief.
        ],
    ],
];
