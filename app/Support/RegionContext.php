<?php

namespace App\Support;

/**
 * Holds the "active region" for the current request, used to scope every
 * region-owned model (see the BelongsToRegion trait).
 *
 *  - Frontend:      set from the visitor's cookie (GB/US/CA).
 *  - Region admin:  set from the admin's own region (locked).
 *  - Super admin:   the region they picked, or "all" (no scoping) for aggregate views.
 */
class RegionContext
{
    protected static ?string $region = null;

    /** True when a super admin is viewing every region at once (no filtering). */
    protected static bool $all = false;

    public static function set(?string $region, bool $all = false): void
    {
        self::$region = $region;
        self::$all = $all;
    }

    /** The active region code (GB/US/CA), or null in "all" mode. */
    public static function region(): ?string
    {
        return self::$region;
    }

    /** Super-admin "all regions" mode — queries are not filtered by region. */
    public static function isAll(): bool
    {
        return self::$all;
    }

    /** Should region-owned queries be filtered right now? */
    public static function hasScope(): bool
    {
        return ! self::$all && self::$region !== null;
    }

    /** Reset to defaults (used in tests / between requests). */
    public static function clear(): void
    {
        self::$region = null;
        self::$all = false;
    }
}
