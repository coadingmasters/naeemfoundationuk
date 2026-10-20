<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Students waiting for a sponsor, from Jamia Binoria's sponsorship API.
 *
 * The API returns each student's full record — phone, passport/ID number,
 * addresses and more. Only the few fields below are ever kept, so nothing
 * private reaches our pages or cache.
 *
 * Results are cached for a short while; if the API is down, the last good
 * list is served instead so the page never goes empty.
 */
class SponsorStudents
{
    private const CACHE_KEY = 'sponsor-students:v3';

    private const STALE_KEY = 'sponsor-students:v3:last-good';

    /** Binoria's fiscal_status_id values. */
    private const STATUS = [1 => 'Wealthy', 2 => 'Needy', 3 => 'Zakat Eligible'];

    /**
     * Every student currently open for sponsorship (needy / Zakat-eligible
     * only), or null when the API has never answered.
     *
     * @return Collection<int, object>|null
     */
    public function all(): ?Collection
    {
        if (! config('services.sponsor_api.key')) {
            return null;
        }

        $students = Cache::remember(self::CACHE_KEY, now()->addMinutes(30), function () {
            $fresh = $this->fetch();
            if ($fresh !== null) {
                Cache::forever(self::STALE_KEY, $fresh);
            }

            return $fresh ?? Cache::get(self::STALE_KEY);
        });

        return $students === null ? null : collect($students)->map(fn ($s) => (object) $s);
    }

    /** One student by their Binoria id, or null. */
    public function find(string $id): ?object
    {
        return $this->all()?->firstWhere('id', $id);
    }

    /** @return array<int, array<string, mixed>>|null */
    private function fetch(): ?array
    {
        try {
            $response = Http::asForm()
                ->timeout(20)
                ->withHeaders(['X-API-SPONSOR-KEY' => config('services.sponsor_api.key')])
                ->post(rtrim(config('services.sponsor_api.url'), '/').'/GetStudentSponsor', [
                    'searchText' => '',
                    'country_id' => 'all',
                    'fiscal_status' => '',
                    'limit' => 5000,
                ]);

            $rows = $response->json();
            if (! $response->successful() || ! is_array($rows) || ! array_is_list($rows)) {
                Log::warning('Sponsor API returned an unexpected response', ['status' => $response->status()]);

                return null;
            }

            return collect($rows)
                ->filter(fn ($r) => is_array($r)
                    && ($r['is_sponsored'] ?? '0') !== '1'
                    && in_array((int) ($r['fiscal_status_id'] ?? 0), [2, 3], true))
                ->map(fn ($r) => $this->publicFields($r))
                ->filter(fn ($s) => $s['id'] !== '' && $s['name'] !== '')
                ->values()
                ->all();
        } catch (Throwable $e) {
            Log::warning('Sponsor API request failed', ['error' => $e->getMessage()]);

            return null;
        }
    }

    /** The only fields we keep — deliberately no contact or ID details. */
    private function publicFields(array $r): array
    {
        $name = $r['std_name_en'] ?? '' ?: ($r['std_official_name'] ?? '' ?: ($r['student_name'] ?? ''));

        $age = null;
        try {
            if (! empty($r['std_dob'])) {
                $age = Carbon::parse($r['std_dob'])->age;
                $age = ($age >= 3 && $age <= 40) ? $age : null;
            }
        } catch (Throwable) {
            $age = null;
        }

        return [
            'id' => (string) ($r['student_id'] ?? ''),
            'name' => trim(preg_replace('/\s+/', ' ', (string) $name)),
            // Urdu name — only used so the site search also matches it.
            'name_alt' => trim(preg_replace('/\s+/', ' ', (string) ($r['student_name'] ?? ''))),
            'country' => trim((string) ($r['country_name'] ?? '')),
            'flag' => (string) ($r['flag_emoji'] ?? ''),
            'age' => $age,
            // Binoria: student_gender 1 = male, 2 = female.
            'gender' => match ((int) ($r['student_gender'] ?? 0)) {
                1 => 'Male', 2 => 'Female', default => null
            },
            'orphan' => (string) ($r['is_orphan'] ?? '0') === '1',
            'status' => self::STATUS[(int) ($r['fiscal_status_id'] ?? 0)] ?? null,
        ];
    }
}
