<?php

namespace App\Support;

/**
 * Resolves the active region (UK / Canada) from the domain the site is served
 * on and exposes its currency, phone, email, address, charity details and
 * Gift Aid setting.
 */
class Country
{
    /** Cookie that stores the chosen region code. */
    public const COOKIE = 'nf_region';

    /** @return array<string, array<string, mixed>> */
    public static function all(): array
    {
        return config('countries.list', []);
    }

    /**
     * The active region code, decided by the domain (naeemfoundation.ca -> CA,
     * everything else -> the default, GB). Visitors don't choose; an old
     * nf_region cookie from when they could is ignored.
     */
    public static function code(): string
    {
        $host = strtolower(request()->getHost());
        $code = config('countries.hosts', [])[$host] ?? null;

        return $code !== null && array_key_exists($code, self::all())
            ? $code
            : config('countries.default', 'GB');
    }

    /** The active region's config array. */
    public static function current(): array
    {
        return self::all()[self::code()];
    }

    /** A single value from the active region (e.g. symbol, phone, currency). */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::current()[$key] ?? $default;
    }

    /** The region is fixed by the domain, so it is always "chosen" (there is no first-visit popup). */
    public static function chosen(): bool
    {
        return true;
    }

    /** Format a money amount with the active region's currency symbol. */
    public static function money(float|int|string $amount, int $decimals = 2): string
    {
        return self::get('symbol', '£').number_format((float) $amount, $decimals);
    }
}
