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

if (! function_exists('socials')) {
    /**
     * The social profiles that have a URL set in config/social.php, ready to
     * render. Anything left blank is omitted, so the site never shows an icon
     * that goes nowhere. Used by the header, mobile drawer, footer and Contact.
     *
     * @return array<int, array{name:string,url:string,icon:string,fill:string,stroke:string}>
     */
    function socials(): array
    {
        $icons = [
            'facebook' => ['Facebook', '<path d="M13 22v-8h2.6l.4-3H13V9c0-.9.3-1.5 1.6-1.5H16V5c-.3 0-1.3-.1-2.3-.1-2.3 0-3.7 1.3-3.7 3.8V11H8v3h2v8h3Z"/>', true],
            'instagram' => ['Instagram', '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>', false],
            'tiktok' => ['TikTok', '<path d="M16 3a5 5 0 0 0 5 5v3a8 8 0 0 1-5-1.8V15a6 6 0 1 1-6-6c.3 0 .7 0 1 .1v3.2A2.8 2.8 0 1 0 13 15V3h3Z"/>', true],
            'x' => ['X', '<path d="M17.5 3h3l-7 8 8.2 10h-6.4l-5-6.1L8 21H5l7.4-8.5L4.5 3h6.5l4.5 5.6L17.5 3Z"/>', true],
            'youtube' => ['YouTube', '<path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.3 5 12 5 12 5s-6.3 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8C5.7 19 12 19 12 19s6.3 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8ZM10 15V9l5 3-5 3Z"/>', true],
        ];

        $out = [];

        foreach ($icons as $key => [$name, $path, $filled]) {
            $url = trim((string) config('social.'.$key, ''));

            if ($url !== '') {
                $out[] = [
                    'name' => $name,
                    'url' => $url,
                    'icon' => $path,
                    'fill' => $filled ? 'currentColor' : 'none',
                    'stroke' => $filled ? 'none' : 'currentColor',
                ];
            }
        }

        return $out;
    }
}

if (! function_exists('region')) {
    /** The active region config, or a single key from it when $key is given. */
    function region(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? Country::current() : Country::get($key, $default);
    }
}
