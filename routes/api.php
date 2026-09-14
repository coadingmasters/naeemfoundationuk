<?php

use App\Http\Controllers\Api\DonationStatsController;
use Illuminate\Support\Facades\Route;

// Public, read-only endpoints only — no auth, no personal donor data. See
// App\Http\Controllers\Api\DonationStatsController for what's returned.
Route::get('donations/uk-summary', [DonationStatsController::class, 'ukSummary'])->name('api.donations.uk-summary');
