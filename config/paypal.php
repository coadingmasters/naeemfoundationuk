<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mode
    |--------------------------------------------------------------------------
    |
    | "sandbox" uses PayPal's test environment (no real money moves).
    | "live" takes real payments. Each mode has its own credentials — a
    | sandbox key will never work against live, and vice versa.
    |
    */

    'mode' => env('PAYPAL_MODE', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | From developer.paypal.com → Apps & Credentials. Keep these in .env only;
    | .env is not committed, so the secret never reaches version control.
    |
    */

    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'base_url' => 'https://api-m.sandbox.paypal.com',
        'webhook_id' => env('PAYPAL_SANDBOX_WEBHOOK_ID'),
    ],

    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'base_url' => 'https://api-m.paypal.com',
        'webhook_id' => env('PAYPAL_LIVE_WEBHOOK_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    |
    | Shown to the donor inside PayPal's own checkout window.
    |
    */

    'brand_name' => env('PAYPAL_BRAND_NAME', config('app.name')),

    /*
    |--------------------------------------------------------------------------
    | Currencies
    |--------------------------------------------------------------------------
    |
    | Region currencies we accept. A currency missing from your PayPal account
    | still works — PayPal converts it into your primary balance.
    |
    */

    'currencies' => ['GBP', 'USD', 'CAD'],

];
