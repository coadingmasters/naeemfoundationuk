<?php

use App\Http\Controllers\Admin\AnnualReportController as AdminAnnualReportController;
use App\Http\Controllers\Admin\AppealController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CauseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\HajjRegistrationController as AdminHajjRegistrationController;
use App\Http\Controllers\Admin\VolunteerController as AdminVolunteerController;
use App\Http\Controllers\Admin\HajjVideoController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrphanController as AdminOrphanController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\AnnualReportController;
use App\Http\Controllers\AskMuftiController;
use App\Http\Controllers\Admin\CommunityVideoController;
use App\Http\Controllers\CambodiaEducationController;
use App\Http\Controllers\CommunityCentreController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EducationSponsorshipsController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductCartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\EidGiftsController;
use App\Http\Controllers\FidyaController;
use App\Http\Controllers\FoodSustenanceController;
use App\Http\Controllers\HajjController;
use App\Http\Controllers\HealthcareController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProstheticLimbController;
use App\Http\Controllers\RamadanFoodPacksController;
use App\Http\Controllers\RamadanTimetableController;
use App\Http\Controllers\SadaqahController;
use App\Http\Controllers\SehriIftarController;
use App\Http\Controllers\SustainableLivelihoodController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\WaterWellController;
use App\Http\Controllers\ZakatUlFitrController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/region/{code}', function (string $code) {
    abort_unless(array_key_exists($code, config('countries.list')), 404);

    return redirect()->back()->withCookie(
        cookie()->forever(\App\Support\Country::COOKIE, $code)
    );
})->name('region.set');
Route::view('/about', 'about')->name('about');
Route::view('/history', 'history')->name('history');
Route::view('/careers', 'career')->name('careers');
Route::get('/annual-report', [AnnualReportController::class, 'index'])->name('annual-report');
Route::get('/news-and-press', [NewsController::class, 'index'])->name('news');
Route::get('/news-and-press/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/zakat', 'zakat')->name('zakat');
Route::view('/zakat-calculator', 'zakat-calculator')->name('zakat-calculator');
Route::view('/dhul-hajj', 'dhul-hajj')->name('dhul-hajj');
Route::view('/qurbani', 'qurbani')->name('qurbani');
Route::get('/fidya-and-kaffarah', [FidyaController::class, 'index'])->name('fidya');
Route::get('/sadaqah', [SadaqahController::class, 'index'])->name('sadaqah');
Route::get('/sehri-and-iftar', [SehriIftarController::class, 'index'])->name('sehri-iftar');
Route::get('/water-well', [WaterWellController::class, 'index'])->name('water-well');
Route::get('/healthcare', [HealthcareController::class, 'index'])->name('healthcare');
Route::get('/food-and-sustenance', [FoodSustenanceController::class, 'index'])->name('food-sustenance');
Route::get('/education-sponsorships', [EducationSponsorshipsController::class, 'index'])->name('education-sponsorships');
Route::view('/hostel-for-students-orphans', 'hostel-for-students-orphans')->name('hostel-for-students-orphans');
Route::view('/clean-water', 'clean-water')->name('clean-water');
Route::get('/orphans-sponsorships', [\App\Http\Controllers\OrphanController::class, 'index'])->name('orphans-sponsorships');
Route::get('/orphans/{orphan}', [\App\Http\Controllers\OrphanController::class, 'show'])->name('orphans.show');
Route::view('/food-appeal', 'food-appeal')->name('food-appeal');
Route::view('/widows', 'widows')->name('widows');
Route::view('/sadaqah-jariyah', 'sadaqah-jariyah')->name('sadaqah-jariyah');
Route::view('/lillah', 'lillah')->name('lillah');
Route::view('/aqiqah', 'aqiqah')->name('aqiqah');
Route::view('/kaffarah', 'kaffarah')->name('kaffarah');
Route::get('/sustainable-livelihood', [SustainableLivelihoodController::class, 'index'])->name('sustainable-livelihood');
Route::get('/cambodia-education-welfare', [CambodiaEducationController::class, 'index'])->name('cambodia-education-welfare');
Route::get('/prosthetic-limb', [ProstheticLimbController::class, 'index'])->name('prosthetic-limb');
Route::view('/ramadan-calendar', 'ramadan-calendar')->name('ramadan-calendar');
Route::get('/schedule-ramadan-giving', [RamadanTimetableController::class, 'index'])->name('schedule-ramadan-giving');
Route::get('/zakat-ul-fitr', [ZakatUlFitrController::class, 'index'])->name('zakat-ul-fitr');
Route::get('/eid-gifts-for-children', [EidGiftsController::class, 'index'])->name('eid-gifts');
Route::get('/ramadan-food-packs', [RamadanFoodPacksController::class, 'index'])->name('ramadan-food-packs');
Route::get('/hajj-2027', [HajjController::class, 'index'])->name('hajj');
Route::post('/hajj-2027/register', [HajjController::class, 'register'])->name('hajj.register');
Route::get('/community-centre', [CommunityCentreController::class, 'index'])->name('community-centre');
Route::post('/community-centre/enquiry', [CommunityCentreController::class, 'enquire'])->name('community-centre.enquire');
Route::get('/ask-a-mufti', [AskMuftiController::class, 'index'])->name('ask-mufti');
Route::post('/ask-a-mufti', [AskMuftiController::class, 'store'])->name('ask-mufti.store');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer');
Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/cart', [ProductCartController::class, 'index'])->name('shop.cart');
Route::post('/shop/cart/add', [ProductCartController::class, 'add'])->name('shop.cart.add');
Route::patch('/shop/cart/{id}', [ProductCartController::class, 'update'])->name('shop.cart.update');
Route::delete('/shop/cart/{id}', [ProductCartController::class, 'remove'])->name('shop.cart.remove');
Route::get('/shop/checkout', [ShopController::class, 'checkout'])->name('shop.checkout');
Route::get('/shop/order-complete', [ShopController::class, 'orderComplete'])->name('shop.order-complete');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::view('/make-a-donation', 'donate.make')->name('donate.make');
Route::get('/appeals/{appeal}', function (\App\Models\Appeal $appeal) {
    abort_unless($appeal->is_active, 404);
    return view('appeals.show', ['appeal' => $appeal]);
})->name('appeals.show');
Route::post('/donate/add', [DonationController::class, 'add'])->name('donate.add');
Route::delete('/donate/remove/{id}', [DonationController::class, 'remove'])->name('donate.remove');
Route::patch('/donate/quantity/{id}', [DonationController::class, 'quantity'])->name('donate.quantity');
Route::get('/address-lookup', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'results' => \App\Support\AddressLookup::search(
            \App\Support\Country::code(),
            (string) $request->query('postcode', '')
        ),
    ]);
})->middleware('throttle:30,1')->name('address.lookup');
Route::get('/address-suggest', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'suggestions' => \App\Support\AddressLookup::suggest(
            \App\Support\Country::code(),
            (string) $request->query('q', '')
        ),
    ]);
})->middleware('throttle:90,1')->name('address.suggest');
Route::get('/donate/checkout', [DonationController::class, 'checkout'])->name('donate.checkout');
Route::post('/donate/checkout', [DonationController::class, 'store'])->name('donate.store');
Route::get('/donate/payment', [DonationController::class, 'payment'])->name('donate.payment');
Route::get('/donate/thank-you', [DonationController::class, 'thankYou'])->name('donate.thank-you');
Route::post('/donate/paypal/order', [PayPalController::class, 'donationOrder'])->name('paypal.donation.order');
Route::post('/donate/paypal/capture', [PayPalController::class, 'donationCapture'])->name('paypal.donation.capture');
Route::post('/donate/paypal/subscription/plan', [PayPalController::class, 'subscriptionPlan'])->name('paypal.subscription.plan');
Route::post('/donate/paypal/subscription/record', [PayPalController::class, 'subscriptionRecord'])->name('paypal.subscription.record');
Route::post('/shop/paypal/order', [PayPalController::class, 'shopOrder'])->name('paypal.shop.order');
Route::post('/shop/paypal/capture', [PayPalController::class, 'shopCapture'])->name('paypal.shop.capture');
Route::post('/paypal/webhook', [PayPalController::class, 'webhook'])->name('paypal.webhook');
$givingRegistered = [];
foreach (config('giving') as $group) {
    foreach (array_merge($group['items'], $group['featured'] ?? []) as $item) {
        if (!empty($item['slug']) && !in_array($item['slug'], $givingRegistered, true)) {
            $givingRegistered[] = $item['slug'];
            Route::view('/give/' . $item['slug'], 'placeholder', [
                'pageTag' => 'Giving',
                'pageTitle' => $item['title'],
                'pageText' => 'Support our ' . $item['title'] . ' programme — this page is being prepared. Thank you for your generosity.',
            ])->name('give.' . $item['slug']);
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
    Route::middleware(['auth', 'admin', 'admin.region'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Super-admin region context switch (All / GB / US / CA).
        Route::get('region/{code}', function (string $code, \Illuminate\Http\Request $request) {
            $user = \Illuminate\Support\Facades\Auth::user();
            abort_unless($user instanceof \App\Models\User && $user->isSuperAdmin(), 403);

            if ($code === 'all') {
                session()->forget('admin_region');
            } elseif (array_key_exists($code, config('countries.list', []))) {
                session(['admin_region' => $code]);
            }

            // Continue to a requested admin page (e.g. the create page they came from).
            $to = (string) $request->query('to', '');
            if ($to !== '' && str_starts_with($to, url('/admin'))) {
                return redirect($to);
            }

            return redirect()->back();
        })->name('region.switch');

        Route::resource('hero-slides', HeroSlideController::class)
            ->except(['show']);

        Route::resource('appeals', AppealController::class)
            ->except(['show']);

        Route::resource('causes', CauseController::class)
            ->except(['show']);

        Route::resource('projects', ProjectController::class)
            ->except(['show']);

        Route::resource('orphans', AdminOrphanController::class)
            ->except(['show']);

        Route::resource('products', AdminProductController::class)
            ->except(['show']);

        // Donations taken through the website (read + detail + delete + export).
        Route::get('donations', [AdminDonationController::class, 'index'])->name('donations.index');
        Route::get('donations/export', [AdminDonationController::class, 'export'])->name('donations.export');
        Route::get('donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
        Route::post('donations/{donation}/cancel-subscription', [AdminDonationController::class, 'cancelSubscription'])->name('donations.cancel-subscription');
        Route::delete('donations/{donation}', [AdminDonationController::class, 'destroy'])->name('donations.destroy');

        // Shop orders placed through checkout (read + status + delete + export).
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        Route::resource('hajj-videos', HajjVideoController::class)
            ->except(['show']);

        // Hajj 2027 registrations submitted through the public form (read + delete + export).
        Route::get('hajj-registrations', [AdminHajjRegistrationController::class, 'index'])->name('hajj-registrations.index');
        Route::get('hajj-registrations/export', [AdminHajjRegistrationController::class, 'export'])->name('hajj-registrations.export');
        Route::delete('hajj-registrations/{registration}', [AdminHajjRegistrationController::class, 'destroy'])->name('hajj-registrations.destroy');

        // Ask a Mufti questions submitted through the public form (read + delete + export).
        Route::get('mufti-questions', [\App\Http\Controllers\Admin\MuftiQuestionController::class, 'index'])->name('mufti-questions.index');
        Route::get('mufti-questions/export', [\App\Http\Controllers\Admin\MuftiQuestionController::class, 'export'])->name('mufti-questions.export');
        Route::get('mufti-questions/{muftiQuestion}', [\App\Http\Controllers\Admin\MuftiQuestionController::class, 'show'])->name('mufti-questions.show');
        Route::post('mufti-questions/{muftiQuestion}/reply', [\App\Http\Controllers\Admin\MuftiQuestionController::class, 'reply'])->name('mufti-questions.reply');
        Route::delete('mufti-questions/{muftiQuestion}', [\App\Http\Controllers\Admin\MuftiQuestionController::class, 'destroy'])->name('mufti-questions.destroy');

        // Volunteer sign-ups submitted through the public form (read + delete + export).
        Route::get('volunteers', [AdminVolunteerController::class, 'index'])->name('volunteers.index');
        Route::get('volunteers/export', [AdminVolunteerController::class, 'export'])->name('volunteers.export');
        Route::delete('volunteers/{volunteer}', [AdminVolunteerController::class, 'destroy'])->name('volunteers.destroy');

        Route::resource('community-videos', CommunityVideoController::class)
            ->except(['show']);

        Route::resource('annual-reports', AdminAnnualReportController::class)
            ->except(['show']);

        Route::resource('news', NewsPostController::class)
            ->parameters(['news' => 'news'])
            ->except(['show']);

        // Admin-user management — super admins only.
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)
            ->middleware('super')
            ->except(['show']);
    });
});
