<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Works out a visitor's country from their IP, and maps it to one of our
 * supported regions (UK / US / Canada) — or null when we don't cover it.
 *
 * A CDN geo header (Cloudflare's CF-IPCountry, etc.) is trusted first: it's
 * free, instant and private. Only when no such header exists do we fall back to
 * a free IP-lookup API, cached per IP so it runs at most once a day per visitor.
 */
class GeoLocation
{
    /** Edge/CDN headers that already carry a 2-letter country code. */
    private const GEO_HEADERS = [
        'CF-IPCountry',            // Cloudflare
        'CloudFront-Viewer-Country', // AWS CloudFront
        'X-Vercel-IP-Country',    // Vercel
        'X-Geo-Country',
        'X-Country-Code',
    ];

    /** The supported region matching the visitor's location, or null. */
    public static function region(Request $request): ?string
    {
        $country = self::countryCode($request);

        if (! $country) {
            return null;
        }

        // Our region codes ARE country codes (GB / US / CA).
        $supported = array_keys(config('countries.list', []));

        return in_array($country, $supported, true) ? $country : null;
    }

    /** The visitor's ISO-3166 alpha-2 country code, or null if unknown. */
    public static function countryCode(Request $request): ?string
    {
        // 1) Trust a CDN geo header if one is present.
        foreach (self::GEO_HEADERS as $header) {
            $value = $request->headers->get($header);
            if (is_string($value) && strlen($value) === 2 && ctype_alpha($value)) {
                return self::normalise($value);
            }
        }

        // 2) Fall back to a free IP lookup (skipped for local/private IPs).
        if (! config('geo.ip_lookup', true)) {
            return null;
        }

        $ip = $request->ip();

        if (! $ip || ! self::isPublic($ip)) {
            return null;
        }

        return Cache::remember('geo:ip:'.$ip, now()->addDay(), function () use ($ip) {
            try {
                $res = Http::timeout(2)->get("http://ip-api.com/json/{$ip}", ['fields' => 'status,countryCode']);

                if ($res->ok() && $res->json('status') === 'success') {
                    return self::normalise((string) $res->json('countryCode'));
                }
            } catch (Throwable $e) {
                // Lookup failed — the popup fallback will handle region selection.
            }

            return null;
        });
    }

    /** UK is sometimes reported as "UK"; the ISO code (and our region) is "GB". */
    private static function normalise(string $code): ?string
    {
        $code = strtoupper(trim($code));

        if ($code === 'UK') {
            $code = 'GB';
        }

        return (strlen($code) === 2 && ctype_alpha($code)) ? $code : null;
    }

    /** Is this a routable public IP (i.e. worth looking up)? */
    private static function isPublic(string $ip): bool
    {
        return (bool) filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
