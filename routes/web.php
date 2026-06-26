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

// "Who We Are" group
Route::view('/about', 'about')->name('about');
Route::view('/careers', 'careers')->name('careers');
Route::view('/history', 'placeholder', [
    'pageTag' => 'Who We Are',
    'pageTitle' => 'Our History',
    'pageText' => 'Two decades of compassion in action — the story of how Naeem Foundation grew from a small local effort into a movement changing lives.',
])->name('history');
Route::view('/annual-report', 'placeholder', [
    'pageTag' => 'Who We Are',
    'pageTitle' => 'Annual Report',
    'pageText' => 'Full transparency on how your donations are used. Our latest annual reports and audited accounts will be published here.',
])->name('annual-report');
Route::view('/news-and-press', 'placeholder', [
    'pageTag' => 'Who We Are',
    'pageTitle' => 'News & Press',
    'pageText' => 'The latest updates, press releases and stories from the field at Naeem Foundation.',
])->name('news');

Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/zakat', 'zakat')->name('zakat');

// "Giving" group — auto-generate a placeholder page for every slug-based item.
foreach (config('giving') as $group) {
    foreach (array_merge($group['items'], $group['featured'] ?? []) as $item) {
        if (! empty($item['slug'])) {
            Route::view('/give/'.$item['slug'], 'placeholder', [
                'pageTag' => 'Giving',
                'pageTitle' => $item['title'],
                'pageText' => 'Support our '.$item['title'].' programme — this page is being prepared. Thank you for your generosity.',
            ])->name('give.'.$item['slug']);
        }
    }
}

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
