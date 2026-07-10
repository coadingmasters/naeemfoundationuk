<?php

use App\Http\Controllers\Admin\AppealController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CauseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HajjVideoController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\AskMuftiController;
use App\Http\Controllers\CambodiaEducationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EidGiftsController;
use App\Http\Controllers\FidyaController;
use App\Http\Controllers\FoodSustenanceController;
use App\Http\Controllers\HajjController;
use App\Http\Controllers\HealthcareController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProstheticLimbController;
use App\Http\Controllers\RamadanFoodPacksController;
use App\Http\Controllers\SadaqahController;
use App\Http\Controllers\SehriIftarController;
use App\Http\Controllers\SustainableLivelihoodController;
use App\Http\Controllers\WaterWellController;
use App\Http\Controllers\ZakatUlFitrController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// "Who We Are" group
Route::view('/about', 'about')->name('about');
Route::view('/history', 'history')->name('history');
Route::view('/careers', 'career')->name('careers');
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
Route::get('/fidya-and-kaffarah', [FidyaController::class, 'index'])->name('fidya');
Route::get('/sadaqah', [SadaqahController::class, 'index'])->name('sadaqah');
Route::get('/sehri-and-iftar', [SehriIftarController::class, 'index'])->name('sehri-iftar');
Route::get('/water-well', [WaterWellController::class, 'index'])->name('water-well');
Route::get('/healthcare', [HealthcareController::class, 'index'])->name('healthcare');
Route::get('/food-and-sustenance', [FoodSustenanceController::class, 'index'])->name('food-sustenance');
Route::get('/sustainable-livelihood', [SustainableLivelihoodController::class, 'index'])->name('sustainable-livelihood');
Route::get('/cambodia-education-welfare', [CambodiaEducationController::class, 'index'])->name('cambodia-education-welfare');
Route::get('/prosthetic-limb', [ProstheticLimbController::class, 'index'])->name('prosthetic-limb');
Route::view('/schedule-ramadan-giving', 'schedule-ramadan-giving')->name('schedule-ramadan-giving');
Route::get('/zakat-ul-fitr', [ZakatUlFitrController::class, 'index'])->name('zakat-ul-fitr');
Route::get('/eid-gifts-for-children', [EidGiftsController::class, 'index'])->name('eid-gifts');
Route::get('/ramadan-food-packs', [RamadanFoodPacksController::class, 'index'])->name('ramadan-food-packs');

Route::get('/hajj-2027', [HajjController::class, 'index'])->name('hajj');
Route::post('/hajj-2027/register', [HajjController::class, 'register'])->name('hajj.register');

Route::get('/ask-a-mufti', [AskMuftiController::class, 'index'])->name('ask-mufti');
Route::post('/ask-a-mufti', [AskMuftiController::class, 'store'])->name('ask-mufti.store');

// Donation basket + checkout
Route::post('/donate/add', [DonationController::class, 'add'])->name('donate.add');
Route::delete('/donate/remove/{id}', [DonationController::class, 'remove'])->name('donate.remove');
Route::patch('/donate/quantity/{id}', [DonationController::class, 'quantity'])->name('donate.quantity');
Route::get('/donate/checkout', [DonationController::class, 'checkout'])->name('donate.checkout');
Route::post('/donate/checkout', [DonationController::class, 'store'])->name('donate.store');
Route::get('/donate/payment', [DonationController::class, 'payment'])->name('donate.payment');
Route::post('/donate/payment', [DonationController::class, 'processPayment'])->name('donate.payment.process');
Route::get('/donate/thank-you', [DonationController::class, 'thankYou'])->name('donate.thank-you');

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

        Route::resource('appeals', AppealController::class)
            ->except(['show']);

        Route::resource('causes', CauseController::class)
            ->except(['show']);

        Route::resource('projects', ProjectController::class)
            ->except(['show']);

        Route::resource('hajj-videos', HajjVideoController::class)
            ->except(['show']);
    });
});
