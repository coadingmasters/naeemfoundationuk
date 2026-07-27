<?php

return [

    /*
    |--------------------------------------------------------------------------
    | IP geolocation
    |--------------------------------------------------------------------------
    |
    | The site auto-detects a first-time visitor's country to pre-select their
    | region (UK / US / Canada). A CDN geo header (e.g. Cloudflare's
    | CF-IPCountry) is used first — free and instant. When no such header is
    | present, we fall back to a free IP lookup API. Set GEO_IP_LOOKUP=false to
    | rely on the CDN header only (recommended if the site is behind Cloudflare).
    |
    */

    'ip_lookup' => (bool) env('GEO_IP_LOOKUP', true),

];
