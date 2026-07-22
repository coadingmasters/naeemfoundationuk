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
            'phone' => '+1 (212) 555-0100',
            'charity_no' => '00-0000000',
            'charity_label' => 'EIN',
            'gift_aid' => true,
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
            'phone' => '+1 (416) 555-0100',
            'charity_no' => '00000 0000 RR0001',
            'charity_label' => 'Charity Reg. No.',
            'gift_aid' => true,
        ],
    ],
];
