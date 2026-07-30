<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Region-aware postcode -> address lookup, using free providers that need no
 * API key:
 *   - UK : postcodes.io      (validates the postcode, returns town/region)
 *   - US : zippopotam.us     (ZIP -> city/state)
 *   - CA : zippopotam.us     (FSA -> city/province)
 *
 * Each result: ['line1' => ?string, 'city' => string, 'state' => string,
 *               'postcode' => string, 'label' => string].
 *
 * For full house-by-house UK addresses, add a paid getaddress.io key — the UK
 * branch then returns every address at the postcode.
 */
class AddressLookup
{
    /**
     * Real-time postcode suggestions as the visitor types (free, no key).
     * UK only — postcodes.io has a free autocomplete endpoint. Other regions
     * return nothing (they use the on-demand "Find" button instead).
     */
    public static function suggest(string $region, string $query): array
    {
        $query = trim($query);

        if (strtoupper($region) !== 'GB' || strlen($query) < 2) {
            return [];
        }

        try {
            $res = Http::timeout(4)->get('https://api.postcodes.io/postcodes/'.rawurlencode($query).'/autocomplete');

            if (! $res->ok()) {
                return [];
            }

            return collect($res->json('result') ?? [])->take(8)->values()->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function search(string $region, string $postcode): array
    {
        $region = strtoupper($region);
        $postcode = trim($postcode);

        if ($postcode === '') {
            return [];
        }

        return match ($region) {
            'GB' => self::uk($postcode),
            'US' => self::zippopotam('us', preg_replace('/\D/', '', $postcode)),
            'CA' => self::zippopotam('ca', strtoupper(substr(preg_replace('/\s+/', '', $postcode), 0, 3))),
            default => [],
        };
    }

    /** UK: full addresses via getaddress.io when configured, else postcodes.io. */
    private static function uk(string $postcode): array
    {
        if (config('address.uk_provider') === 'getaddress' && config('address.getaddress_key')) {
            return self::getAddress($postcode);
        }

        try {
            $res = Http::timeout(5)->get('https://api.postcodes.io/postcodes/'.rawurlencode($postcode));

            if (! $res->ok() || ! $res->json('result')) {
                return [];
            }

            $r = $res->json('result');
            $city = $r['admin_district'] ?? ($r['region'] ?? '');

            return [[
                'line1' => null,
                'city' => $city,
                'state' => $r['region'] ?? '',
                'postcode' => $r['postcode'] ?? $postcode,
                'label' => trim(($r['admin_ward'] ? $r['admin_ward'].', ' : '').$city.', '.($r['postcode'] ?? $postcode)),
            ]];
        } catch (Throwable $e) {
            return [];
        }
    }

    /** US / CA: locality lookup (may return several places). */
    private static function zippopotam(string $country, string $query): array
    {
        if ($query === '') {
            return [];
        }

        try {
            $res = Http::timeout(5)->get("https://api.zippopotam.us/{$country}/{$query}");

            if (! $res->ok()) {
                return [];
            }

            $post = (string) $res->json('post code', $query);

            return collect($res->json('places', []))
                ->map(fn ($p) => [
                    'line1' => null,
                    'city' => $p['place name'] ?? '',
                    'state' => $p['state'] ?? '',
                    'postcode' => $post,
                    'label' => trim(($p['place name'] ?? '').', '.($p['state abbreviation'] ?? ($p['state'] ?? '')).' '.$post),
                ])
                ->filter(fn ($x) => $x['city'] !== '')
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }

    /** UK full addresses (paid getaddress.io). Only used when a key is set. */
    private static function getAddress(string $postcode): array
    {
        try {
            $res = Http::timeout(6)->get('https://api.getaddress.io/find/'.rawurlencode($postcode), [
                'api-key' => config('address.getaddress_key'),
                'expand' => 'true',
            ]);

            if (! $res->ok()) {
                return [];
            }

            return collect($res->json('addresses', []))
                ->map(function ($a) use ($postcode) {
                    $line1 = trim(implode(' ', array_filter([$a['line_1'] ?? '', $a['line_2'] ?? ''])));
                    $city = $a['town_or_city'] ?? '';

                    return [
                        'line1' => $line1,
                        'city' => $city,
                        'state' => $a['county'] ?? '',
                        'postcode' => strtoupper($postcode),
                        'label' => trim($line1.($city ? ', '.$city : '')),
                    ];
                })
                ->filter(fn ($x) => $x['label'] !== '')
                ->values()
                ->all();
        } catch (Throwable $e) {
            return [];
        }
    }
}
