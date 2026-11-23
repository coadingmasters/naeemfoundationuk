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
    // (The old crmbackend.rentpe.co.uk host now answers "Invalid API key".)
    'webhook_url' => env('CRM_WEBHOOK_URL', 'https://api.lumrick.com/api/webhooks/donations/cart'),

    'timeout' => 20,

    // Recurring gifts (weekly/monthly/yearly) are paid through the CRM's
    // GoCardless connection. While that is not working, keep this false: a
    // basket with a recurring gift then stays on the site's own PayPal flow so
    // donors never hit an error. Set CRM_RECURRING=true once GoCardless works.
    'recurring' => env('CRM_RECURRING', false),

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
    | "Donation API" — the site takes the payment itself (PayPal), then reports
    | the PAID donation to the CRM so it is recorded there. Nothing is charged
    | by the CRM and the donor is not redirected. It has its own key + secret
    | (different from the hosted-checkout ones above); a currency with no
    | record keys is simply not reported. Independent of 'enabled' above.
    */
    'record' => [
        'enabled' => env('CRM_RECORD_ENABLED', true),
        'url' => env('CRM_RECORD_URL', 'https://api.lumrick.com/api/webhooks/donations'),
        'timeout' => 10,
        'branches' => [
            'GBP' => ['api_key' => env('CRM_RECORD_GBP_API_KEY'), 'signing_secret' => env('CRM_RECORD_GBP_SIGNING_SECRET')],
            'USD' => ['api_key' => env('CRM_RECORD_USD_API_KEY'), 'signing_secret' => env('CRM_RECORD_USD_SIGNING_SECRET')],
            'CAD' => ['api_key' => env('CRM_RECORD_CAD_API_KEY'), 'signing_secret' => env('CRM_RECORD_CAD_SIGNING_SECRET')],
        ],
    ],

    /*
    | Site cause -> CRM campaign. A cause title is matched (case-insensitive)
    | against the 'match' words in order — first hit wins, so keep the more
    | specific rules first. 'type' is the CRM donationType. The CRM only accepts
    | a fixed list (Zakat, Sadaqah, General, Fidya, Kaffarah, Fitrana, Qurbani,
    | Aqeeqah); anything not listed here is sent as 'General'.
    */
    'default_campaign' => '44eaa5a7-b1d9-4aa0-9d54-ed8a11862c79', // Naeem Foundation Donation

    'campaigns' => [
        ['match' => ['zakat ul fitr', 'zakat-ul-fitr', 'fitrana', 'fitra', 'eid gift'], 'id' => '97910e28-d29c-4527-ba85-fa3b194949b0', 'type' => 'Fitrana'], // Fitrana & Eid Gift
        ['match' => ['zakat'], 'id' => 'd721e793-a3f4-441d-b8bd-a28f283a3ea9', 'type' => 'Zakat'],
        ['match' => ['sadaqah', 'sadaqa'], 'id' => '2f1928d6-ec20-4762-b611-bac7124ff8f8', 'type' => 'Sadaqah'],
        ['match' => ['kaffarah'], 'id' => 'cbdf5ef5-7124-4f4e-96fb-5e32bce7fdd6', 'type' => 'Kaffarah'], // Fidya & Kaffarah
        ['match' => ['fidya'], 'id' => 'cbdf5ef5-7124-4f4e-96fb-5e32bce7fdd6', 'type' => 'Fidya'], // Fidya & Kaffarah
        ['match' => ['aqeeqah'], 'id' => '35cd190b-6c09-4a65-8a89-1a605eba8721', 'type' => 'Aqeeqah'], // Qurbani 2026
        ['match' => ['qurbani', 'udhiya'], 'id' => '35cd190b-6c09-4a65-8a89-1a605eba8721', 'type' => 'Qurbani'], // Qurbani 2026
        ['match' => ['gaza'], 'id' => '09727e4f-0b71-4196-9f77-31accc672188', 'type' => 'General'], // Appeal For Gaza
        ['match' => ['flood'], 'id' => '62079d64-08f9-44f8-9d5f-017734801572', 'type' => 'General'], // Flood Donation
        ['match' => ['cambodia'], 'id' => '9da36070-f1f8-4b2a-8374-864418e377be', 'type' => 'General'], // Cambodia Education
        ['match' => ['prosthetic', 'limb'], 'id' => '0831ec80-af8f-4ce2-91bf-2b8a813b252c', 'type' => 'General'], // Prosthetic Limb
        ['match' => ['binoria'], 'id' => '19c02215-72ff-4b13-9502-d1f18bc92b1c', 'type' => 'General'], // Binoria Water Campaign
        ['match' => ['water', 'well', 'handpump', 'pump'], 'id' => 'db7b1301-2803-4830-a424-22581d92e9cc', 'type' => 'General'], // Water Pump
        ['match' => ['sehri', 'seher', 'iftar'], 'id' => '5a1ea858-59d9-4380-81d5-58eb3c70f898', 'type' => 'General'], // Seher & Iftar
        ['match' => ['ration'], 'id' => '289b643d-f9a7-4cf2-8aa6-08f5d55847e2', 'type' => 'General'], // Ration
        ['match' => ['food', 'meal', 'sustenance', 'feed'], 'id' => '687a7d22-f2eb-46d1-ae3b-fc48105b52a3', 'type' => 'General'], // Food & Sustenance
        ['match' => ['health', 'medical'], 'id' => '586c2279-b78c-4fdb-8f81-5b64db6cd1b5', 'type' => 'General'], // Health Care
        ['match' => ['education', 'school', 'student'], 'id' => 'f8972812-3658-4313-a844-6a5c99dd8046', 'type' => 'General'], // Education
        ['match' => ['community', 'islamic centre', 'masjid'], 'id' => 'bd402fca-d58b-40c0-be5b-ace13a550345', 'type' => 'General'], // Community & Islamic Centre
        ['match' => ['friday'], 'id' => '4ba9b343-113e-4d19-b9ba-50098251ed62', 'type' => 'General'], // Friday Prayer
        ['match' => ['ten nights', 'dhul hajj', 'dhul-hijjah', 'dhul hijjah'], 'id' => 'f9a9ab22-49ce-4812-94a1-a0c90e884b5d', 'type' => 'General'], // Naeem Foundation Ten Nights
    ],
];
