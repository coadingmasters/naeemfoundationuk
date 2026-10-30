<?php

/*
|--------------------------------------------------------------------------
| CRM (Lumrick) donation webhook
|--------------------------------------------------------------------------
|
| When enabled, website donations are NOT charged by this site. The cart is
| sent (signed, server-to-server) to the CRM, which returns a hosted checkout
| page; the donor pays there and is sent back to /donate/thank-you. One-off
| and recurring items can sit in the same cart — every item carries its own
| frequency. The shop (products) still uses PayPal.
|
| Secrets live in .env only. Never put them in browser code or commit them.
|
*/

return [
    // Master switch. While false the site keeps using its own PayPal flow.
    'enabled' => env('CRM_ENABLED', false),

    // Same URL for every branch — the API key decides which branch is used.
    'webhook_url' => env('CRM_WEBHOOK_URL', 'https://crmbackend.rentpe.co.uk/api/webhooks/donations/cart'),

    'timeout' => 20,

    /*
    | Private test link. While 'enabled' is false the CRM checkout is hidden
    | from donors, but anyone who opens /donate/crm-preview/{this token} gets it
    | in their own browser session only. Leave empty to disable the link.
    */
    'preview_token' => env('CRM_PREVIEW_TOKEN'),

    /*
    | One API key + signing secret per CRM branch. The branch decides the
    | currency, so credentials are keyed by currency. A currency with no
    | credentials keeps using the site's own PayPal flow, so regions can be
    | switched over one at a time (add CRM_USD_* / CRM_CAD_* when ready).
    */
    'branches' => [
        'GBP' => [
            'api_key' => env('CRM_GBP_API_KEY'),
            'signing_secret' => env('CRM_GBP_SIGNING_SECRET'),
        ],
        'USD' => [
            'api_key' => env('CRM_USD_API_KEY'),
            'signing_secret' => env('CRM_USD_SIGNING_SECRET'),
        ],
        'CAD' => [
            'api_key' => env('CRM_CAD_API_KEY'),
            'signing_secret' => env('CRM_CAD_SIGNING_SECRET'),
        ],
    ],

    /*
    | Site cause -> CRM campaign. A cause title is matched (case-insensitive)
    | against the 'match' words in order — first hit wins, so keep the more
    | specific rules first. 'type' is the CRM donationType; when null the
    | cause's own title is sent instead.
    */
    'default_campaign' => '1da40652-0671-44f7-a5a8-6b786d0413f7', // Naeem Foundation Donation

    'campaigns' => [
        ['match' => ['zakat ul fitr', 'zakat-ul-fitr', 'fitrana', 'fitra', 'eid gift'], 'id' => '285251a7-ffd8-4160-b0a1-edfbd05c562e', 'type' => null], // Fitrana & Eid Gift
        ['match' => ['zakat'], 'id' => 'e21d1288-021d-461b-9e87-5cee3e44ac19', 'type' => 'Zakat'],
        ['match' => ['sadaqah', 'sadaqa'], 'id' => '9b7a5dda-930a-4092-8514-7c13b4ef548b', 'type' => 'Sadaqah'],
        ['match' => ['fidya', 'kaffarah'], 'id' => '8a50340c-e938-4523-a824-35d94d377bad', 'type' => null], // Fidya & Kaffarah
        ['match' => ['qurbani', 'udhiya', 'aqeeqah'], 'id' => '8da79768-f673-4ec6-8ba5-3d8bb16902b7', 'type' => null], // Qurbani 2026
        ['match' => ['gaza'], 'id' => '840d8aa0-fe1c-4e9a-9ac0-68ef74cc0862', 'type' => null], // Appeal For Gaza
        ['match' => ['flood'], 'id' => '1c9858a9-f074-4a45-9da4-9e8e8dc22a3c', 'type' => null], // Flood Donation
        ['match' => ['cambodia'], 'id' => '954dbaf9-4b07-4149-94e7-48e732da7ce7', 'type' => null], // Cambodia Education
        ['match' => ['prosthetic', 'limb'], 'id' => 'dcbc292c-9ac0-4b77-8dce-d70dcd1d31dd', 'type' => null], // Prosthetic Limb
        ['match' => ['binoria'], 'id' => '0c386a0c-262d-4cdc-b5fe-98a9fe38fd59', 'type' => null], // Binoria Water Campaign
        ['match' => ['water', 'well', 'handpump', 'pump'], 'id' => '51d722b0-78d3-4d89-8847-60fcb93b1bae', 'type' => null], // Water Pump
        ['match' => ['sehri', 'seher', 'iftar'], 'id' => '008eac0c-4050-4985-87de-62c44e36dbca', 'type' => null], // Seher & Iftar
        ['match' => ['ration'], 'id' => '5821cb4f-5461-498c-a840-908254298c8d', 'type' => null], // Ration
        ['match' => ['food', 'meal', 'sustenance', 'feed'], 'id' => 'd3bdf595-1f96-4fa6-a268-64a698064cc6', 'type' => null], // Food & Sustenance
        ['match' => ['health', 'medical'], 'id' => '43882661-94ba-40bf-81f3-bc4b7da01b33', 'type' => null], // Health Care
        ['match' => ['education', 'school', 'student'], 'id' => '81deb54d-f635-430e-bb5c-460bada5b7a6', 'type' => null], // Education
        ['match' => ['community', 'islamic centre', 'masjid'], 'id' => '44db7265-81c8-40e1-8e15-3ac97afb38d9', 'type' => null], // Community & Islamic Centre
        ['match' => ['friday'], 'id' => '5cd8de44-fd8b-4aa3-9ca8-bb929248fd1a', 'type' => null], // Friday Prayer
        ['match' => ['ten nights', 'dhul hajj', 'dhul-hijjah', 'dhul hijjah'], 'id' => '6e4d80cc-6a59-48e7-a397-f587ec86e812', 'type' => null], // Naeem Foundation Ten Nights
    ],
];
