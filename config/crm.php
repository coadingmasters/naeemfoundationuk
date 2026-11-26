<?php

/*
|--------------------------------------------------------------------------
| CRM (Lumrick) "Donation API"
|--------------------------------------------------------------------------
|
| The site takes every payment itself (PayPal). Right after a payment succeeds
| the PAID donation is reported to the CRM (signed, server-to-server) so it is
| recorded there; recurring gifts appear under subscriptions. Nothing is charged
| by the CRM and the donor is never redirected.
|
| Secrets live in .env only. Never put them in browser code or commit them.
|
*/

return [
    'record' => [
        // Master switch for reporting. A failure never affects the donor.
        'enabled' => env('CRM_RECORD_ENABLED', true),
        'url' => env('CRM_RECORD_URL', 'https://api.lumrick.com/api/webhooks/donations'),
        'timeout' => 10,

        // One API key + signing secret per CRM branch. The branch decides the
        // currency, so credentials are keyed by currency; a currency without
        // keys is simply not reported.
        'branches' => [
            'GBP' => ['api_key' => env('CRM_RECORD_GBP_API_KEY'), 'signing_secret' => env('CRM_RECORD_GBP_SIGNING_SECRET')],
            'USD' => ['api_key' => env('CRM_RECORD_USD_API_KEY'), 'signing_secret' => env('CRM_RECORD_USD_SIGNING_SECRET')],
            'CAD' => ['api_key' => env('CRM_RECORD_CAD_API_KEY'), 'signing_secret' => env('CRM_RECORD_CAD_SIGNING_SECRET')],
        ],
    ],

    /*
    | Site cause -> CRM campaign. Only the campaigns of the website's Giving Menu
    | exist here (the CRM has one campaign per menu entry).
    |
    | A cause title is matched (case-insensitive) against the 'match' words in
    | order — first hit wins, so more specific rules come first. A word that
    | starts with "/" is a regular expression. 'type' is the CRM donationType
    | (Sadaqah, Fitrana, Zakat, Fidya, Qurbani, Aqeeqah, Kaffarah, General,
    | WaterPump, Education, …). A null type on a scheduled-giving rule means
    | "the type of the cause that was scheduled" (see 'schedule_types').
    |
    | Anything that matches no rule goes to the default campaign (Where Most Needed).
    */
    'default_campaign' => 'fedd5b15-da2b-4b96-b5ff-91c8257293f0', // Where Most Needed

    // For scheduled giving ("Zakat (Every Friday)"): the cause word -> donation type.
    'schedule_types' => ['zakat' => 'Zakat', 'sadaqah' => 'Sadaqah', 'education' => 'Education'],

    'campaigns' => [
        // Scheduled giving pages (Friday / Daily / Monthly / Ramadan) — the schedule decides the campaign.
        ['match' => ['friday giving', 'every friday'], 'id' => '264563b6-6e0b-4a7f-b5e3-5ea7526766e3', 'type' => null], // Friday Giving
        ['match' => ['daily giving', 'every day'], 'id' => '41ee9588-df9e-4d18-8770-76513400d842', 'type' => null], // Daily Giving
        ['match' => ['monthly giving', 'every month'], 'id' => 'dfb26e6e-87b6-4c31-9ab2-7f6a02ed7091', 'type' => null], // Monthly Giving
        ['match' => ['ramadan giving', '/ramadan \d+ nights/'], 'id' => 'c8e39cde-52b9-4f57-a7a9-7c5636a2828d', 'type' => null], // Ramadan Giving

        ['match' => ['dhul hajj giving'], 'id' => '1b3d41d6-764a-40ed-90b6-88e2a20cef62', 'type' => 'General'], // Dhul Hajj Giving
        ['match' => ['dhul hajj', 'dhul-hijjah', 'dhul hijjah', 'ten nights'], 'id' => '9cffd318-5f82-430e-9e54-49bdfb813edd', 'type' => 'General'], // Dhul Hajj
        ['match' => ['hajj'], 'id' => 'dfb57345-235f-41aa-ad5e-6bdf9eb8852c', 'type' => 'General'], // Hajj 2027

        ['match' => ['ramadan calendar'], 'id' => '647d7f7a-0536-4022-9d04-60e84ac73d6f', 'type' => 'General'], // Ramadan Calendar
        ['match' => ['ramadan food'], 'id' => 'e7618f81-853f-40d3-b0c1-fb377e107ffe', 'type' => 'RamadanFoodPack'], // Ramadan Food Bags
        ['match' => ['zakat calculator'], 'id' => '89bb2a73-4696-4047-b78a-25a81a26457b', 'type' => 'Zakat'], // Zakat Calculator
        ['match' => ['zakat ul fitr', 'zakat-ul-fitr', 'fitrana', 'fitra'], 'id' => 'b40569dc-f697-4842-b8a9-f2fd245b449a', 'type' => 'Fitrana'], // Zakat ul Fitr
        ['match' => ['eid gift'], 'id' => '3ff3de40-d9c7-4845-8865-aec974a934c8', 'type' => 'EidGifts'], // Eid Gifts
        ['match' => ['zakat'], 'id' => 'e1f07166-59f6-4797-9bd1-0d597b0aff74', 'type' => 'Zakat'], // Zakat

        ['match' => ['sadaqah jariyah', 'sadaqa jariyah', 'sadaqah-jariyah'], 'id' => '1afb59b9-be22-4063-a6a3-b136ee15e0c1', 'type' => 'Sadaqah'], // Sadaqah Jariyah
        ['match' => ['sadaqah', 'sadaqa'], 'id' => '9f4793cc-9ed1-4f62-bf4b-c33f022d57c1', 'type' => 'Sadaqah'], // Sadaqah
        ['match' => ['lillah'], 'id' => '6d50093d-7d09-4490-9ab6-8c6d5a13e4a4', 'type' => 'Lilah'], // Lillah
        ['match' => ['aqeeqah', 'aqiqah', 'aqiqa'], 'id' => 'd62dccf2-bf70-488b-a39f-73852aadeea8', 'type' => 'Aqeeqah'], // Aqiqah
        ['match' => ['qurbani', 'udhiya'], 'id' => '79ef0e31-b511-4092-93ac-55d049171ae3', 'type' => 'Qurbani'], // Qurbani
        ['match' => ['kaffarah'], 'id' => '247c2c21-72a2-4354-8c3c-8345264d5f3d', 'type' => 'Kaffarah'], // Kaffarah
        ['match' => ['fidya'], 'id' => '7252a505-4c3b-46e5-9c87-b214af9348e9', 'type' => 'Fidya'], // Fidya
        ['match' => ['sehri', 'seher', 'iftar'], 'id' => '1f5776f6-8c19-4406-915f-aced73b5728b', 'type' => 'SehrIftar'], // Seher & Iftar

        // Orphans and widows (the add-on gifts too: "Books for an Orphan", "Support a Widow" …).
        ['match' => ['hostel'], 'id' => 'd9e4a723-fe95-4289-9854-73cbb4b35514', 'type' => 'General'], // Hostel for Students / Orphans
        ['match' => ['widow'], 'id' => '67d407be-9e57-4f33-a8b2-474b43913bd2', 'type' => 'General'], // Widows Support
        ['match' => ['orphan'], 'id' => '88c7887f-e1b3-453f-a8eb-08e8e96dd863', 'type' => 'General'], // Orphans Sponsorships

        ['match' => ['livelihood'], 'id' => 'c47c0f54-e2aa-4043-b647-fb4b316ec520', 'type' => 'General'], // Sustainable Livelihood
        ['match' => ['prosthetic', 'limb'], 'id' => 'a23b59cc-e7f9-4455-a50b-966dd708ba7f', 'type' => 'General'], // Prosthetic Limb
        ['match' => ['water', 'well', 'handpump', 'pump'], 'id' => 'e82047eb-c1e5-4dc9-9687-a693c3c72f1b', 'type' => 'WaterPump'], // Clean Water
        ['match' => ['cambodia', 'school meal'], 'id' => '876082d6-1ea5-46e8-b351-7ec02537ff96', 'type' => 'General'], // Cambodia Education & Welfare
        ['match' => ['food appeal'], 'id' => '45dd7eec-d65a-4de3-8378-8aec026ed8be', 'type' => 'General'], // Food Appeal
        ['match' => ['food', 'meal', 'sustenance', 'feed', 'ration'], 'id' => 'f2e2fc87-4ac0-436a-951d-0ce8ec9785fb', 'type' => 'General'], // Food & Sustenance
        ['match' => ['health', 'medical'], 'id' => 'cab69d92-4908-4e4d-b76b-76d4c5020866', 'type' => 'General'], // Healthcare
        ['match' => ['education', 'school', 'student'], 'id' => '71369324-566b-42b7-89ce-552d76537367', 'type' => 'Education'], // Education Sponsorships
    ],
];
