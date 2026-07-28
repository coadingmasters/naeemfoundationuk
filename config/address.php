<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Address lookup (postcode -> address)
    |--------------------------------------------------------------------------
    |
    | Multi-region postcode lookup on the donation/checkout forms:
    |   - UK  : postcodes.io  (free, no key) — validates + fills town/region.
    |   - US  : zippopotam    (free, no key) — ZIP -> city/state.
    |   - CA  : zippopotam    (free, no key) — FSA -> city/province.
    |
    | For full house-by-house UK addresses, set ADDRESS_UK_PROVIDER=getaddress and
    | add a GETADDRESS_KEY (paid, getaddress.io). The UI then lists each address.
    |
    */

    'uk_provider' => env('ADDRESS_UK_PROVIDER', 'postcodes'), // postcodes | getaddress

    'getaddress_key' => env('GETADDRESS_KEY'),

];
