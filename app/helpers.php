<?php

use App\Support\Country;

if (! function_exists('money')) {
    /**
     * Format an amount in the visitor's active currency (£ / $ / CA$).
     * Usage in Blade: {{ money($product->price) }} or {{ money(50, 0) }}
     */
    function money(float|int|string $amount, int $decimals = 2): string
    {
        return Country::money($amount, $decimals);
    }
}

if (! function_exists('region')) {
    /** The active region config, or a single key from it when $key is given. */
    function region(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? Country::current() : Country::get($key, $default);
    }
}
