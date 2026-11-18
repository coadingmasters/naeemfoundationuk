<?php

/*
|--------------------------------------------------------------------------
| Regions / countries the site operates in
|--------------------------------------------------------------------------
| The region comes from the domain the visitor is on (see `hosts`). It drives
| the currency, phone number, email, address, charity registration number,
| social links and Gift Aid availability across the whole site.
|
| Pages and content are the same everywhere — only these values change.
*/

return [
    // Used for every domain not listed in `hosts` (naeemfoundation.co.uk).
    'default' => 'GB',

    // Domain -> region. A visitor on naeemfoundation.ca sees Canadian details.
    'hosts' => [
        'naeemfoundation.ca' => 'CA',
        'www.naeemfoundation.ca' => 'CA',
    ],

    // Admin-managed content (causes, appeals, slides…) is scoped to this region.
    // One install serving both domains shows the same content on each (GB); a
    // separate install for Canada sets CONTENT_REGION=CA in its .env, so its admin
    // manages Canadian content.
    'content_region' => env('CONTENT_REGION', 'GB'),

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
            'footer_phone' => '+44 7960185682 : +44 7960185682',
            'mobile' => '+44 7960 185682',
            'email' => 'donate@naeemfoundation.co.uk',
            'hajj_email' => 'hajj@naeemfoundation.co.uk',
            'address' => [
                '2 Falcon Gate, Shire Park',
                'Welwyn Garden City, AL7 1TW',
                'United Kingdom',
            ],
            'website' => 'https://naeemfoundation.co.uk',
            // Bank-transfer details shown in the footer and on the privacy page.
            'bank' => [
                'bank' => 'Lloyds Bank',
                'account_name' => 'Naeem Foundation',
                'sort_code' => '30-54-66',
                'account_number' => '13783063',
            ],
            'charity_no' => '1199466',
            'charity_label' => 'Registered Charity No.',
            'gift_aid' => true,
            'fundraising_regulator' => true, // UK-only regulator badge.
            'show_vacancy' => true,          // Careers page shows the open role + CV form.
            'show_annual_report' => true,    // Annual Report page + its menu/footer links.
            'show_team' => true,             // About page "Talented Team" (board) section.
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
            'phone' => '+1 437 333 4342',
            'footer_phone' => '+1 437 333 4342',
            'mobile' => '+1 437 333 4342',
            'email' => 'contact@naeemfoundation.ca',
            'hajj_email' => 'contact@naeemfoundation.ca',
            'address' => [
                '1403 Hill Street',
                'Milton, ON, L9T 6M6',
                'Canada',
            ],
            'website' => 'https://naeemfoundation.ca',
            // Social profiles for this domain (only the ones listed show).
            'social' => [
                'facebook' => 'https://www.facebook.com/naeemfoundationcanada',
                'instagram' => 'https://www.instagram.com/naeemfoundationcanada/',
            ],
            // Bank-transfer details shown in the footer and on the privacy page.
            'bank' => [
                'bank' => 'TD Canada Trust',
                'account_name' => 'Naeem Foundation Canada',
                'account_number' => '18685239071',
                'swift' => 'TDOMCATTTOR',
            ],
            'charity_no' => '721376226RR0001',
            'charity_label' => 'Charity No.',
            'gift_aid' => false, // Gift Aid is a UK-only tax relief.
        ],
    ],
];
