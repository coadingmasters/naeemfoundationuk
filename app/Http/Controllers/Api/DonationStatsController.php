<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Public, read-only donation totals — aggregate figures only, never
 * individual donor details (name, email, address, etc.). Safe to share and
 * embed anywhere; see config/cors.php for why every origin is allowed.
 */
class DonationStatsController extends Controller
{
    public function ukSummary(): JsonResponse
    {
        $summary = Cache::remember('api.donation-stats.uk-summary', now()->addMinutes(5), function () {
            $paid = Donation::forRegion('GB')->where('status', 'paid');

            return [
                'total_raised' => (float) $paid->clone()->sum('total'),
                'donation_count' => $paid->clone()->count(),
                'currency' => 'GBP',
                'updated_at' => now()->toIso8601String(),
            ];
        });

        return response()->json([
            'region' => 'UK',
            ...$summary,
        ]);
    }
}
