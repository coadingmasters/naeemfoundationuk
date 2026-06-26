<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/zakat', 'zakat')->name('zakat');
Route::view('/history', 'careers')->name('history');

/*
|--------------------------------------------------------------------------
| Admin authentication
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    /*
    |----------------------------------------------------------------------
    | Admin dashboard (authenticated administrators only)
    |----------------------------------------------------------------------
    */
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('hero-slides', HeroSlideController::class)
            ->except(['show']);
    });
});
