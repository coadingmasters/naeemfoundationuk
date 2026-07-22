<?php

namespace App\Support;

/**
 * Resolves the visitor's active region (UK / US / Canada) from a cookie and
 * exposes its currency, phone number, charity details and Gift Aid setting.
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

    /** The active region code, falling back to the configured default. */
    public static function code(): string
    {
        $code = request()->cookie(self::COOKIE);

        return array_key_exists($code, self::all()) ? $code : config('countries.default', 'GB');
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

    /** Has the visitor explicitly chosen a region yet? Drives the first-visit popup. */
    public static function chosen(): bool
    {
        return array_key_exists(request()->cookie(self::COOKIE), self::all());
    }

    /** Format a money amount with the active region's currency symbol. */
    public static function money(float|int|string $amount, int $decimals = 2): string
    {
        return self::get('symbol', '£').number_format((float) $amount, $decimals);
    }
}
